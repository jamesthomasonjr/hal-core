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
use QL\Hal\Core\Entity\EncryptedProperty;
use QL\Hal\Core\Entity\EventLog;
use QL\Hal\Core\Entity\Push;
use QL\Hal\Core\Entity\Token;

/**
 * A doctrine event listener for:
 * - Add a "CreatedTime" TimePoint to persisted objects when initially created.
 *     - AuditLog
 *     - Build
 *     - Push
 *     - EventLog
 * - Add a random sha hash as the unique identifier.
 *     - EncryptedProperty
 *     - EventLog
 *     - Token
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
     * @type callable
     */
    private $random;

    /**
     * @param Clock $clock
     * @param callable $random
     */
    public function __construct(Clock $clock, callable $random)
    {
        $this->clock = $clock;
        $this->random = $random;
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
        if (
            $entity instanceof EventLog ||
            $entity instanceof AuditLog ||
            $entity instanceof Build ||
            $entity instanceof Push
        ) {
            if (!$entity->created()) {
                $created = $this->clock->read();
                $entity->withCreated($created);
            }
        }

        // Add unique generated id
        if (
            $entity instanceof EventLog ||
            $entity instanceof EncryptedProperty ||
            $entity instanceof Token
        ) {
            if (!$entity->id()) {
                $id = call_user_func($this->random);
                $entity->withId($id);
            }
        }
    }
}
