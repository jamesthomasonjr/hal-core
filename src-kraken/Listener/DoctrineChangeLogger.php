<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Listener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use QL\Hal\Core\Entity\User;
use QL\Kraken\Core\Entity\AuditLog;
use QL\Kraken\Core\Entity\Property;
use QL\Kraken\Core\Entity\Schema;
use QL\MCP\Common\Time\Clock;

class DoctrineChangeLogger
{
    const ACTION_CREATE = 'CREATE';
    const ACTION_UPDATE = 'UPDATE';
    const ACTION_DELETE = 'DELETE';

    /**
     * @type Clock
     */
    private $clock;

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
        // Only log Schema and Property
        if (!$entity instanceof Schema && !$entity instanceof Property) {
            return;
        }

        $fqcn = explode('\\', get_class($entity));
        $classname = array_pop($fqcn);
        $namespace = implode('\\', $fqcn);

        // Only log entities in "QL\Kraken\Core\Entity" namespace
        if ($namespace !== 'QL\Kraken\Core\Entity') {
            return;
        }

        // figure out the entity primary id
        $entityId = $entity->id() ? $entity->id() : '?';
        $object = sprintf('%s:%s', $classname, $entityId);

        // Get the property key
        if ($entity instanceof Schema) {
            $key = $entity->key();
        } else {
            $key = $entity->schema() ? $entity->schema()->key() : '';
        }

        $id = call_user_func($this->random);
        $log = (new AuditLog($id))
            ->withEntity($object)
            ->withKey($key)
            ->withAction($action)
            ->withData(json_encode($entity))
            ->withUser($user);

        if ($entity->application()) {
            $log->withApplication($entity->application());
        }

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
