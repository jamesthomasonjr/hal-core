<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Entity;

use PHPUnit_Framework_TestCase;
use QL\Hal\Core\Entity\User;
use QL\MCP\Common\Time\TimePoint;

class AuditLogTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $application = new AuditLog;

        $this->assertSame('', $application->id());
        $this->assertSame(null, $application->created());
        $this->assertSame('', $application->entity());
        $this->assertSame('', $application->key());
        $this->assertSame('', $application->action());
        $this->assertSame('', $application->data());

        $this->assertSame(null, $application->user());
        $this->assertSame(null, $application->application());
    }

    public function testProperties()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $user = new User;
        $application = new Application;

        $log = (new AuditLog('abcd'))
            ->withCreated($time)
            ->withEntity('Snapshot:abcdef')
            ->withKey('namespace.config_property')
            ->withAction('DELETE')
            ->withData('data')
            ->withUser($user)
            ->withApplication($application);

        $this->assertSame('abcd', $log->id());
        $this->assertSame($time, $log->created());
        $this->assertSame('Snapshot:abcdef', $log->entity());
        $this->assertSame('namespace.config_property', $log->key());
        $this->assertSame('DELETE', $log->action());
        $this->assertSame('data', $log->data());

        $this->assertSame($user, $log->user());
        $this->assertSame($application, $log->application());
    }
}
