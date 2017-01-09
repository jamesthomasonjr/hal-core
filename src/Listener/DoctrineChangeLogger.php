<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\AuditLog;
use QL\Hal\Core\Entity\Credential;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\EncryptedProperty;
use QL\Hal\Core\Entity\Environment;
use QL\Hal\Core\Entity\Group;
use QL\Hal\Core\Entity\Server;
use QL\Hal\Core\Entity\User;
use QL\Hal\Core\Entity\UserPermission;
use QL\Hal\Core\Entity\UserType;
use QL\MCP\Common\Time\Clock;

class DoctrineChangeLogger
{
    const ACTION_CREATE = 'CREATE';
    const ACTION_UPDATE = 'UPDATE';
    const ACTION_DELETE = 'DELETE';

    /**
     * @var Clock
     */
    private $clock;

    /**
     * @var callable
     */
    private $random;

    /**
     * @var callable
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
     *
     * @return void
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
            if ($log = $this->log($user, $entity, $uow, self::ACTION_CREATE)) {
                $this->persist($em, $uow, $log);
            }
        }

        // Entity Updates
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($log = $this->log($user, $entity, $uow, self::ACTION_UPDATE)) {
                $this->persist($em, $uow, $log);
            }
        }

        // Entity Deletions
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($log = $this->log($user, $entity, $uow, self::ACTION_DELETE)) {
                $this->persist($em, $uow, $log);
            }
        }
    }



    /**
     * @param mixed $entity
     *
     * @return bool
     */
    private function shouldLog($entity)
    {
        if (
            $entity instanceof Application ||
            $entity instanceof Credential ||
            $entity instanceof EncryptedProperty ||
            $entity instanceof Environment ||
            $entity instanceof Group ||
            $entity instanceof Server ||
            $entity instanceof UserPermission ||
            $entity instanceof UserType ||
            $entity instanceof Deployment
        ) {
            return true;
        }

        return false;
    }

    /**
     * Prepare an audit log from a changed entity.
     *
     * @param User $user
     * @param mixed $entity
     * @param UnitOfWork $uow
     * @param string $action
     *
     * @return AuditLog|null
     */
    private function log(User $user, $entity, UnitOfWork $uow, $action)
    {
        if (!$this->shouldLog($entity)) {
            return;
        }

        $fqcn = explode('\\', get_class($entity));
        $classname = array_pop($fqcn);

        // figure out the entity primary id
        $id = '?';
        $entityId = $entity->id() ? $entity->id() : '?';
        $object = sprintf('%s:%s', $classname, $entityId);

        $data = json_encode($entity);
        if ($action === self::ACTION_UPDATE) {
            $changeset = $uow->getEntityChangeSet($entity);

            // bomb out if deployment and only change is updating the push
            if ($entity instanceof Deployment && array_keys($changeset) === ['push']) {
                return;
            }

            $data = $this->withChangeset($data, $changeset);
        }

        $id = call_user_func($this->random);
        $log = (new AuditLog($id))
            ->withEntity($object)
            ->withAction($action)
            ->withData($data)
            ->withUser($user);

        return $log;
    }

    /**
     * @param string $data
     * @param array $changeset
     *
     * @return array
     */
    private function withChangeset($data, array $changeset)
    {
        $data = json_decode($data, true);

        foreach ($changeset as $field => $properties) {
            if (isset($data[$field])) {
                $data[$field] = [
                    'current' => $properties[0],
                    'new' => $properties[1]
                ];
            }
        }

        return json_encode($data);
    }

    /**
     * Persist the audit log.
     *
     * @param ObjectManager $em
     * @param UnitOfWork $unit
     * @param AuditLog $log
     *
     * @return null
     */
    private function persist(ObjectManager $em, UnitOfWork $unit, AuditLog $log)
    {
        $em->persist($log);

        $meta = $em->getClassMetadata(AuditLog::CLASS);
        $unit->computeChangeSet($meta, $log);
    }
}
