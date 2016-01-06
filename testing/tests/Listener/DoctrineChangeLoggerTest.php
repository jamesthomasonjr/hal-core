<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Listener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use MCP\DataType\Time\Clock;
use Mockery;
use PHPUnit_Framework_TestCase;
use QL\Hal\Core\Entity\AuditLog;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Entity\Group;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Push;
use QL\Hal\Core\Entity\Server;
use QL\Hal\Core\Entity\User;
use stdClass;

class DoctrineChangeLoggerTest extends PHPUnit_Framework_TestCase
{
    private $clock;
    private $user;

    private $uow;
    private $em;
    private $eventArgs;

    private $random;
    private $lazyUser;

    public function setUp()
    {
        $this->clock = Mockery::mock(Clock::CLASS);
        $this->user = Mockery::mock(User::CLASS, [
            'id' => 1234
        ]);

        $this->uow = Mockery::mock(UnitOfWork::CLASS, [
            'getScheduledEntityInsertions' => [],
            'getScheduledEntityUpdates' => [],
            'getScheduledEntityDeletions' => [],
        ]);
        $this->em = Mockery::mock(EntityManagerInterface::CLASS, [
            'getUnitOfWork' => $this->uow
        ]);
        $this->eventArgs = Mockery::mock(OnFlushEventArgs::CLASS, [
            'getEntityManager' => $this->em
        ]);

        $this->random = function() {return 2;};
        $this->lazyUser = function() {
            return $this->user;
        };
    }

    public function testNoUserFoundDoesNothing()
    {
        $notfoundUser = function() {};

        $this->em
            ->shouldReceive('find')
            ->never();

        $logger = new DoctrineChangeLogger($this->clock, $this->random, $notfoundUser);
        $logger->onFlush($this->eventArgs);
    }

    public function testNoValidUserFoundDoesNothing()
    {
        $invalidUser = function() {return 1;};

        $this->em
            ->shouldReceive('find')
            ->never();

        $logger = new DoctrineChangeLogger($this->clock, $this->random, $invalidUser);
        $logger->onFlush($this->eventArgs);
    }

    public function testNoValidDBUserFoundDoesNothing()
    {
        $this->em
            ->shouldReceive('find')
            ->with(User::CLASS, 1234)
            ->andReturnNull();
        $this->uow
            ->shouldReceive('getScheduledEntityInsertions')
            ->never();

        $logger = new DoctrineChangeLogger($this->clock, $this->random, $this->lazyUser);
        $logger->onFlush($this->eventArgs);
    }

    public function testMultipleInsertsAndDeletionsLogged()
    {
        $this->em
            ->shouldReceive('find')
            ->with(User::CLASS, 1234)
            ->andReturn($this->user);
        $this->em
            ->shouldReceive('getClassMetadata')
            ->with(AuditLog::CLASS)
            ->andReturn(Mockery::mock(ClassMetadata::CLASS))
            ->once();

        $deploymentSpy = null;
        $serverSpy = null;
        $this->em
            ->shouldReceive('persist')
            ->with(Mockery::on(function ($v) use (&$deploymentSpy) {
                if (stripos($v->entity(), 'Deployment') !== false) {
                    $deploymentSpy = $v;
                    return true;
                }
            }))
            ->once();

        $this->em
            ->shouldReceive('persist')
            ->with(Mockery::on(function ($v) use (&$serverSpy) {
                if (stripos($v->entity(), 'Server') !== false) {
                    $serverSpy = $v;
                    return true;
                }
            }))
            ->once();

        $this->uow
            ->shouldReceive('getScheduledEntityInsertions')
            ->andReturn([
                // Skipped
                new User,
                new AuditLog,
                new Build,
                new Push,

                // Logged
                new Deployment,

                // Not Logged, invalid
                new stdClass
            ]);

        $this->uow
            ->shouldReceive('getScheduledEntityDeletions')
            ->andReturn([
                // Skipped
                new User,
                new AuditLog,
                new Build,
                new Push,

                // Logged
                (new Server)->withId(5),

                // Not Logged, invalid
                new stdClass
            ]);

        $this->uow
            ->shouldReceive('computeChangeSet')
            ->with(Mockery::any(), Mockery::type(AuditLog::CLASS))
            ->twice();

        $logger = new DoctrineChangeLogger($this->clock, $this->random, $this->lazyUser);
        $logger->onFlush($this->eventArgs);

        $expectedEncodedDeployment = <<<JSON
{"id":null,"name":"","url":"","path":null,"cdName":null,"cdGroup":null,"cdConfiguration":null,"ebName":null,"ebEnvironment":null,"ec2Pool":null,"s3bucket":null,"s3file":null,"application":null,"server":null,"credential":null,"push":null}
JSON;

        $expectedEncodedServer = <<<JSON
{"id":5,"type":"","name":"","environment":null}
JSON;

        // Deployment log
        $this->assertInstanceOf(AuditLog::CLASS, $deploymentSpy);
        $this->assertSame('Deployment:?', $deploymentSpy->entity());
        $this->assertSame('CREATE', $deploymentSpy->action());
        $this->assertSame($expectedEncodedDeployment, $deploymentSpy->data());
        $this->assertSame($this->user, $deploymentSpy->user());

        // Server log
        $this->assertInstanceOf(AuditLog::CLASS, $serverSpy);
        $this->assertSame('Server:5', $serverSpy->entity());
        $this->assertSame('DELETE', $serverSpy->action());
        $this->assertSame($expectedEncodedServer, $serverSpy->data());
        $this->assertSame($this->user, $serverSpy->user());
    }

    public function testChangeSetRecorded()
    {
        $this->em
            ->shouldReceive('find')
            ->with(User::CLASS, 1234)
            ->andReturn($this->user);
        $this->em
            ->shouldReceive('getClassMetadata')
            ->with(AuditLog::CLASS)
            ->andReturn(Mockery::mock(ClassMetadata::CLASS))
            ->once();

        $groupSpy = null;

        $this->em
            ->shouldReceive('persist')
            ->with(Mockery::on(function ($v) use (&$groupSpy) {
                if (stripos($v->entity(), 'Group') !== false) {
                    $groupSpy = $v;
                    return true;
                }
            }))
            ->once();

        $this->uow
            ->shouldReceive('getScheduledEntityUpdates')
            ->andReturn([
                // Skipped
                new User,
                new AuditLog,

                // Logged
                (new Group)->withId(5),

                // Not Logged, invalid
                new stdClass
            ]);

        $this->uow
            ->shouldReceive('computeChangeSet')
            ->with(Mockery::any(), Mockery::type(AuditLog::CLASS))
            ->once();

        $this->uow
            ->shouldReceive('getEntityChangeSet')
            ->andReturn([
                'identifier' => ['derp', 'derp2'],
                'name' => ['herp', 'herp2']
            ])
            ->once();

        $logger = new DoctrineChangeLogger($this->clock, $this->random, $this->lazyUser);
        $logger->onFlush($this->eventArgs);

        $expectedEncodedGroup = <<<JSON
{"id":5,"identifier":{"current":"derp","new":"derp2"},"name":{"current":"herp","new":"herp2"}}
JSON;
        // Deployment log
        $this->assertInstanceOf(AuditLog::CLASS, $groupSpy);
        $this->assertSame('Group:5', $groupSpy->entity());
        $this->assertSame('UPDATE', $groupSpy->action());
        $this->assertSame($expectedEncodedGroup, $groupSpy->data());
        $this->assertSame($this->user, $groupSpy->user());
    }
}
