<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use MCP\DataType\Time\Clock;
use QL\Hal\Core\Entity\AuditLog;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Entity\EventLog;
use QL\Hal\Core\Entity\Push;

/**
 * A doctrine event listener for:
 * - Add a "CreatedTime" TimePoint to persisted objects when initially created.
 *     - AuditLog
 *     - Build
 *     - Push
 *     - EventLog
 *
 * It should be attached to the PrePersist event.
 *
 * Default timestamps are done through code and not the database so we can maintain timezone consistency.
 */
class DoctrinePersistListener
{
    /**
     * @type Clock
     */
    private $clock;

    /**
     * @param Clock $clock
     */
    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    /**
     * Listen for Doctrine prePersist events.
     *
     * Ensure that entities have a "Created Time" when they are created.
     *
     * @param EventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();

        // Add created time
        if ($this->isTimestampable($entity)) {
            if (!$entity->created()) {
                $created = $this->clock->read();
                $entity->withCreated($created);
            }
        }
    }

    /**
     * @param mixed $entity
     *
     * @return bool
     */
    private function isTimestampable($entity)
    {
        if ($entity instanceof EventLog) return true;
        if ($entity instanceof AuditLog) return true;
        if ($entity instanceof Build) return true;
        if ($entity instanceof Push) return true;

        return false;
    }
}
