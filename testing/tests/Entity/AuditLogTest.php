<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use MCP\DataType\Time\TimePoint;
use PHPUnit_Framework_TestCase;

class AuditLogTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $cred = new AuditLog;

        $this->assertSame('', $cred->id());
        $this->assertSame(null, $cred->created());
        $this->assertSame('', $cred->entity());
        $this->assertSame('', $cred->action());
        $this->assertSame('', $cred->data());
        $this->assertSame(null, $cred->user());
    }

    public function testProperties()
    {
        $user = new User;
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $cred = (new AuditLog)
            ->withId('1234')
            ->withCreated($time)
            ->withEntity('Entity:Id')
            ->withAction('DELETE')
            ->withData('context-data-here')
            ->withUser($user);

        $this->assertSame('1234', $cred->id());
        $this->assertSame($time, $cred->created());
        $this->assertSame('Entity:Id', $cred->entity());
        $this->assertSame('DELETE', $cred->action());
        $this->assertSame('context-data-here', $cred->data());
        $this->assertSame($user, $cred->user());
    }
}
