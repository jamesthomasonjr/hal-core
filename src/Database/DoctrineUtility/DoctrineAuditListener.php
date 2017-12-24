<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Database\DoctrineUtility;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use Hal\Core\Entity\Application;
use Hal\Core\Entity\AuditEvent;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Organization;
use Hal\Core\Entity\User;
use Hal\Core\Type\AuditActionEnum;

class DoctrineAuditListener
{
    /**
     * @var callable|null
     */
    private $ownerFetcher;

    /**
     * @param callable $ownerFetcher
     */
    public function setLazyOwnerFetcher(callable $ownerFetcher)
    {
        $this->ownerFetcher = $ownerFetcher;
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

        $owner = $this->getOwner($em);

        // Entity Insertions
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($audit = $this->log($entity, $uow, AuditActionEnum::TYPE_CREATE, $owner)) {
                $this->saveAudit($em, $uow, $audit);
            }
        }

        // Entity Updates
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($audit = $this->log($entity, $uow, AuditActionEnum::TYPE_UPDATE, $owner)) {
                $audit = $this->withChangeset($entity, $audit, $uow);
                $this->saveAudit($em, $uow, $audit);
            }
        }

        // Entity Deletions
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($audit = $this->log($entity, $uow, AuditActionEnum::TYPE_DELETE, $owner)) {
                $this->saveAudit($em, $uow, $audit);
            }
        }
    }

    /**
     * @param EntityManagerInterface $em
     *
     * @return string
     */
    private function getOwner(EntityManagerInterface $em)
    {
        if (!$this->ownerFetcher) {
            return 'Unknown';
        }

        $user = call_user_func($this->ownerFetcher);

        if (!$user instanceof User) {
            return 'Unknown';
        }

        if ($user = $em->find(User::class, $user->id())) {
            return $user->username();
        }

        return 'Unknown';
    }

    /**
     * Prepare an audit log from a changed entity.
     *
     * @param mixed $entity
     * @param UnitOfWork $uow
     * @param string $action
     * @param string $owner
     *
     * @return AuditEvent|null
     */
    private function log($entity, UnitOfWork $uow, $action, $owner)
    {
        if (!$this->shouldLog($entity)) {
            return;
        }

        $fqcn = explode('\\', get_class($entity));
        $classname = array_pop($fqcn);

        $object = sprintf('%s:%s', $classname, $entity->id() ?: '?');
        $data = json_encode($entity);

        $event = (new AuditEvent)
            ->withAction($action)
            ->withOwner($owner)
            ->withEntity($object)
            ->withData($data);

        return $event;
    }

    /**
     * @param mixed $entity
     *
     * @return bool
     */
    private function shouldLog($entity)
    {
        $entities = [
            Application::class,
            Environment::class,
            Organization::class
        ];

        foreach ($entities as $allowEntity) {
            if ($entity instanceof $allowEntity) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $entity
     * @param AuditEvent $event
     * @param UnitOfWork $uow
     *
     * @return AuditEvent
     */
    private function withChangeset($entity, AuditEvent $event, UnitOfWork $uow)
    {
        $data = $event->data();
        $changeset = $uow->getEntityChangeSet($entity);

        $data = $this->mergeChangeset($data, $changeset);

        return $event->withData($data);
    }

    /**
     * Add the changes in this update to the audit event data.
     *
     * @param string $data
     * @param array $changeset
     *
     * @return array
     */
    private function mergeChangeset($data, array $changeset)
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
     * Save the audit event.
     *
     * @param ObjectManager $em
     * @param UnitOfWork $unit
     * @param AuditEvent $event
     *
     * @return null
     */
    private function saveAudit(ObjectManager $em, UnitOfWork $unit, AuditEvent $event)
    {
        $em->persist($event);

        $meta = $em->getClassMetadata(AuditEvent::class);
        $unit->computeChangeSet($meta, $event);
    }
}
