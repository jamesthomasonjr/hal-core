<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class AuditEventTest extends TestCase
{
    public function testDefaultValues()
    {
        $event = new AuditEvent;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $event->id());
        $this->assertInstanceOf(TimePoint::class, $event->created());

        $this->assertSame('create', $event->action());
        $this->assertSame('', $event->actor());
        $this->assertSame('', $event->description());
    }

    public function testProperties()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $event = (new AuditEvent('1234', $time))
            ->withAction('delete')
            ->withActor('testuser')
            ->withDescription('User testuser deleted entity Application AppName')
            ->withParameters(['test' => 'context-data-here']);

        $this->assertSame('1234', $event->id());
        $this->assertSame($time, $event->created());

        $this->assertSame('delete', $event->action());
        $this->assertSame('testuser', $event->actor());
        $this->assertSame('User testuser deleted entity Application AppName', $event->description());
        $this->assertSame('context-data-here', $event->parameter('test'));
    }

    public function testInvalidEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid AuditActionEnum option.');

        $event = new AuditEvent('id');
        $event->withAction('derp');
    }
}
