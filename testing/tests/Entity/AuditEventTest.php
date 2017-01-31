<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Type\EnumException;
use PHPUnit_Framework_TestCase;
use QL\MCP\Common\Time\TimePoint;

class AuditEventTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $event = new AuditEvent;

        $this->assertStringMatchesFormat('%x', $event->id());
        $this->assertInstanceOf(TimePoint::class, $event->created());
        $this->assertSame('', $event->entity());
        $this->assertSame('create', $event->action());
        $this->assertSame('', $event->data());
        $this->assertSame('', $event->owner());
    }

    public function testProperties()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $event = (new AuditEvent)
            ->withID('1234')
            ->withCreated($time)
            ->withEntity('Entity:Id')
            ->withAction('delete')
            ->withData('context-data-here')
            ->withOwner('testuser');

        $this->assertSame('1234', $event->id());
        $this->assertSame($time, $event->created());
        $this->assertSame('Entity:Id', $event->entity());
        $this->assertSame('delete', $event->action());
        $this->assertSame('context-data-here', $event->data());
        $this->assertSame('testuser', $event->owner());
    }

    public function testInvalidEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid audit option.');

        $event = new AuditEvent('id');
        $event->withAction('derp');
    }
}
