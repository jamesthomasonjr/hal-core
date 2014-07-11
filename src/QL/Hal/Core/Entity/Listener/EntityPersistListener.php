<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use MCP\DataType\Time\Clock;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Entity\Push;

/**
 * A doctrine event listener for adding a "CreatedTime" TimePoint to persisted objects when initially created.
 *
 * It should be attached to the PrePersist event.
 *
 * Default timestamps are done through code and not the database so we can maintain timezone consistency.
 */
class EntityPersistListener
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
        if ($entity instanceof Build || $entity instanceof Push) {
            if (!$entity->getCreated()) {
                $created = $this->clock->read();
                $entity->setCreated($created);
            }
        }
    }
}
