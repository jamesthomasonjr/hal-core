<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use QL\Kraken\Core\Entity\AuditLog;
use QL\Kraken\Core\Entity\Configuration;
use QL\Kraken\Core\Entity\Property;
use QL\Kraken\Core\Entity\Schema;
use QL\Kraken\Core\Entity\Snapshot;
use QL\MCP\Common\Time\Clock;

/**
 * A doctrine event listener for:
 * - Add a "created" TimePoint to persisted objects when initially created.
 *     - Configuration
 *     - Property
 *     - Schema
 *     - Snapshot
 *
 * It should be attached to the PrePersist event.
 *
 * Default timestamps are done through code and not the database so we can maintain timezone consistency.
 */
class DoctrinePersistListener
{
    /**
     * @var Clock
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
        if ($entity instanceof AuditLog) return true;
        if ($entity instanceof Configuration) return true;
        if ($entity instanceof Property) return true;
        if ($entity instanceof Schema) return true;
        if ($entity instanceof Snapshot) return true;

        return false;
    }
}
