<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Listener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use MCP\DataType\Time\Clock;
use QL\Hal\Core\Entity\AuditLog;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Entity\Push;
use QL\Hal\Core\Entity\User;

class DoctrineChangeLogger
{
    const ACTION_CREATE = 'CREATE';
    const ACTION_UPDATE = 'UPDATE';
    const ACTION_DELETE = 'DELETE';

    /**
     * @type callable
     */
    private $lazyUser;

    /**
     * @type Clock
     */
    private $random;

    /**
     * @type callable
     */
    private $lazyUser;

    /**
     * @param Clock $clock
     * @param callable $random
     * @param callable $lazyUser
     */
    public function __construct(Clock $clock, callable $random, callable $lazyUser)
    {
        $this->clock = $clock;
        $this->random = $random;
        $this->lazyUser = $lazyUser;
    }
    /**
     * Listen for Doctrine flush events.
     *
     * This listener will catch any entities or collections scheduled for insert, update, or removal.
     *
     * @param OnFlushEventArgs $event
     */
    public function onFlush(OnFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        if (!$user = call_user_func($this->lazyUser)) {
            return;
        }

        if (!$user instanceof User) {
            return;
        }

        if (!$user = $em->find(User::CLASS, $user->id())) {
            return;
        }

        // Entity Insertions
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($log = $this->log($user, $entity, self::ACTION_CREATE)) {
                $this->persist($em, $uow, $log);
            }
        }

        // Entity Updates
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($log = $this->log($user, $entity, self::ACTION_UPDATE)) {
                $this->addChangeset($log, $uow->getEntityChangeSet($entity));
                $this->persist($em, $uow, $log);
            }
        }

        // Entity Deletions
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($log = $this->log($user, $entity, self::ACTION_DELETE)) {
                $this->persist($em, $uow, $log);
            }
        }
    }

    /**
     * Prepare an audit log from a changed entity.
     *
     * @param User $user
     * @param mixed $entity
     * @param string $action
     *
     * @return AuditLog|null
     */
    private function log(User $user, $entity, $action)
    {
        // prevent logging loop
        if ($entity instanceof AuditLog || $entity instanceof User) {
            return;
        }

        // Skip builds and pushes, since those are always attached to a user anyway.
        if ($entity instanceof Build || $entity instanceof Push) {
            return;
        }

        $fqcn = explode('\\', get_class($entity));
        $classname = array_pop($fqcn);
        $namespace = implode('\\', $fqcn);

        // Only log entities in "QL\Hal\Core\Entity" namespace
        if ($namespace !== 'QL\Hal\Core\Entity') {
            return;
        }

        // figure out the entity primary id
        $id = '?';
        $entityId = $entity->id() ? $entity->id() : '?';
        $object = sprintf('%s:%s', $classname, $entityId);

        $id = call_user_func($this->random);
        $log = (new AuditLog($id))
            ->withEntity($object)
            ->withAction($action)
            ->withData(json_encode($entity))
            ->withUser($user);

        return $log;
    }

    /**
     * @param AuditLog $log
     * @param array $changeset
     *
     * @return null
     */
    private function addChangeset(AuditLog $log, array $changeset)
    {
        $data = json_decode($log->data(), true);

        foreach ($changeset as $field => $properties) {
            if (isset($data[$field])) {
                $data[$field] = [
                    'current' => $properties[0],
                    'new' => $properties[1]
                ];
            }
        }

        $log->withData(json_encode($data));
    }

    /**
     * Persist the audit log.
     *
     * @param EntityManager $em
     * @param UnitOfWork $unit
     * @param AuditLog $log
     *
     * @return null
     */
    private function persist(EntityManager $em, UnitOfWork $unit, AuditLog $log)
    {
        $em->persist($log);

        $meta = $em->getClassMetadata(AuditLog::CLASS);
        $unit->computeChangeSet($meta, $log);
    }
}
