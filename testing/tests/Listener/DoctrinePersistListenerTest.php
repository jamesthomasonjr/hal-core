<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use MCP\DataType\Time\Clock;
use MCP\DataType\Time\TimePoint;
use Mockery;
use PHPUnit_Framework_TestCase;
use QL\Hal\Core\Entity\AuditLog;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Entity\EventLog;
use QL\Hal\Core\Entity\Group;
use QL\Hal\Core\Entity\Push;

class DoctrinePersistListenerTest extends PHPUnit_Framework_TestCase
{
    private $clock;

    public function setUp()
    {
        $this->clock = new Clock('2015-08-10 02:30:00', 'UTC');
    }

    /**
     * @dataProvider timestampablesProvider
     */
    public function testTimestampeableEntitiesAreTimestamped($entityClassName)
    {
        $listener = new DoctrinePersistListener($this->clock);

        $entity = Mockery::mock($entityClassName);

        $entity
            ->shouldReceive('created')
            ->andReturnNull()
            ->once();

        $entity
            ->shouldReceive('withCreated')
            ->with(Mockery::type(TimePoint::CLASS))
            ->once();

        $event = Mockery::mock(LifecycleEventArgs::CLASS, [
            'getObject' => $entity
        ]);

        $listener->prePersist($event);
    }

    public function testTimestampableEntityWithTimeDoesNothing()
    {
        $listener = new DoctrinePersistListener($this->clock);

        $entity = Mockery::mock(AuditLog::CLASS);

        $entity
            ->shouldReceive('created')
            ->andReturn(Mockery::type(TimePoint::CLASS))
            ->once();

        $entity
            ->shouldReceive('withCreated')
            ->never();

        $event = Mockery::mock(LifecycleEventArgs::CLASS, [
            'getObject' => $entity
        ]);

        $listener->prePersist($event);
    }

    public function testNonTimestampableEntityDoesNothing()
    {
        $listener = new DoctrinePersistListener($this->clock);

        $entity = Mockery::mock(Group::CLASS);

        $entity
            ->shouldReceive('created')
            ->never();
        $entity
            ->shouldReceive('withCreated')
            ->never();

        $event = Mockery::mock(LifecycleEventArgs::CLASS, [
            'getObject' => $entity
        ]);

        $listener->prePersist($event);
    }

    public function timestampablesProvider()
    {
        return [
            [AuditLog::CLASS],
            [Build::CLASS],
            [EventLog::CLASS],
            [Push::CLASS]
        ];
    }
}
