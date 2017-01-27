<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\DoctrineUtility;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use Hal\Core\Entity\Application;
use Hal\Core\Entity\AuditEvent;
use Hal\Core\Entity\Build;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Organization;
use Hal\Core\Entity\Release;
use Hal\Core\Entity\User;
use Mockery;
use PHPUnit_Framework_TestCase;
use stdClass;

class DoctrineChangeListenerTest extends PHPUnit_Framework_TestCase
{
    private $user;

    private $uow;
    private $em;
    private $eventArgs;

    private $lazyUser;

    public function setUp()
    {
        $this->user = Mockery::mock(User::class, [
            'id' => 1234,
            'username' => 'testuser'
        ]);

        $this->uow = Mockery::mock(UnitOfWork::class, [
            'getScheduledEntityInsertions' => [],
            'getScheduledEntityUpdates' => [],
            'getScheduledEntityDeletions' => [],
        ]);
        $this->em = Mockery::mock(EntityManagerInterface::class, [
            'getUnitOfWork' => $this->uow
        ]);
        $this->eventArgs = Mockery::mock(OnFlushEventArgs::class, [
            'getEntityManager' => $this->em
        ]);

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

        $logger = new DoctrineChangeListener($notfoundUser);
        $logger->onFlush($this->eventArgs);
    }

    public function testNoValidUserFoundDoesNothing()
    {
        $invalidUser = function() {return 1;};

        $this->em
            ->shouldReceive('find')
            ->never();

        $logger = new DoctrineChangeListener($invalidUser);
        $logger->onFlush($this->eventArgs);
    }

    public function testNoValidDBUserFoundDoesNothing()
    {
        $this->em
            ->shouldReceive('find')
            ->with(User::class, 1234)
            ->andReturnNull();
        $this->uow
            ->shouldReceive('getScheduledEntityInsertions')
            ->never();

        $logger = new DoctrineChangeListener($this->lazyUser);
        $logger->onFlush($this->eventArgs);
    }

    public function testMultipleInsertsAndDeletionsLogged()
    {
        $this->em
            ->shouldReceive('find')
            ->with(User::class, 1234)
            ->andReturn($this->user);
        $this->em
            ->shouldReceive('getClassMetadata')
            ->with(AuditEvent::class)
            ->andReturn(Mockery::mock(ClassMetadata::class))
            ->once();

        $appSpy = null;
        $orgSpy = null;
        $this->em
            ->shouldReceive('persist')
            ->with(Mockery::on(function ($v) use (&$appSpy) {
                if (stripos($v->entity(), 'Application') !== false) {
                    $appSpy = $v;
                    return true;
                }
            }))
            ->once();

        $this->em
            ->shouldReceive('persist')
            ->with(Mockery::on(function ($v) use (&$orgSpy) {
                if (stripos($v->entity(), 'Organization') !== false) {
                    $orgSpy = $v;
                    return true;
                }
            }))
            ->once();

        $this->uow
            ->shouldReceive('getScheduledEntityInsertions')
            ->andReturn([
                // Skipped
                new User('abc'),
                new AuditEvent('def'),
                new Build('ghi'),
                new Release('jkl'),

                // Logged
                new Application('mno'),

                // Not Logged, invalid
                new stdClass
            ]);

        $this->uow
            ->shouldReceive('getScheduledEntityDeletions')
            ->andReturn([
                // Skipped
                new User('abc2'),
                new AuditEvent('def2'),
                new Build('ghi2'),
                new Release('jkl2'),

                // Logged
                new Organization('mno2'),

                // Not Logged, invalid
                new stdClass
            ]);

        $this->uow
            ->shouldReceive('computeChangeSet')
            ->with(Mockery::any(), Mockery::type(AuditEvent::class))
            ->twice();

        $logger = new DoctrineChangeListener($this->lazyUser);
        $logger->onFlush($this->eventArgs);

        $expectedEncodedApp = <<<JSON
{"id":"mno","identifier":"","name":"","github":{"owner":"","repository":""},"organization_id":null}
JSON;

        $expectedEncodedOrg = <<<JSON
{"id":"mno2","identifier":"","name":""}
JSON;

        // app event
        $this->assertInstanceOf(AuditEvent::class, $appSpy);
        $this->assertSame('Application:mno', $appSpy->entity());
        $this->assertSame('create', $appSpy->action());
        $this->assertSame($expectedEncodedApp, $appSpy->data());
        $this->assertSame('testuser', $appSpy->owner());

        // org event
        $this->assertInstanceOf(AuditEvent::class, $orgSpy);
        $this->assertSame('Organization:mno2', $orgSpy->entity());
        $this->assertSame('delete', $orgSpy->action());
        $this->assertSame($expectedEncodedOrg, $orgSpy->data());
        $this->assertSame('testuser', $orgSpy->owner());
    }

    public function testChangeSetRecorded()
    {
        $this->em
            ->shouldReceive('find')
            ->with(User::class, 1234)
            ->andReturn($this->user);
        $this->em
            ->shouldReceive('getClassMetadata')
            ->with(AuditEvent::class)
            ->andReturn(Mockery::mock(ClassMetadata::class))
            ->once();

        $envSpy = null;
        $this->em
            ->shouldReceive('persist')
            ->with(Mockery::on(function ($v) use (&$envSpy) {
                if (stripos($v->entity(), 'Environment') !== false) {
                    $envSpy = $v;
                    return true;
                }
            }))
            ->once();

        $this->uow
            ->shouldReceive('getScheduledEntityUpdates')
            ->andReturn([
                // Skipped
                new User,
                new AuditEvent,

                // Logged
                new Environment('5'),

                // Not Logged, invalid
                new stdClass
            ]);

        $this->uow
            ->shouldReceive('computeChangeSet')
            ->with(Mockery::any(), Mockery::type(AuditEvent::class))
            ->once();

        $this->uow
            ->shouldReceive('getEntityChangeSet')
            ->andReturn([
                'is_production' => [true, false],
                'name' => ['herp', 'herp2']
            ])
            ->once();

        $logger = new DoctrineChangeListener($this->lazyUser);
        $logger->onFlush($this->eventArgs);

        $expectedEncodedEnv = <<<JSON
{"id":"5","name":{"current":"herp","new":"herp2"},"is_production":{"current":true,"new":false}}
JSON;
        // env event
        $this->assertInstanceOf(AuditEvent::class, $envSpy);

        $this->assertSame('update', $envSpy->action());
        $this->assertSame('testuser', $envSpy->owner());

        $this->assertSame('Environment:5', $envSpy->entity());
        $this->assertSame($expectedEncodedEnv, $envSpy->data());
    }
}
