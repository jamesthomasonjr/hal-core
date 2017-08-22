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

class JobEventTest extends TestCase
{
    public function testDefaultValues()
    {
        $event = new JobEvent;

        $this->assertStringMatchesFormat('%x', $event->id());
        $this->assertSame('unknown', $event->stage());
        $this->assertSame(0, $event->order());
        $this->assertInstanceOf(TimePoint::class, $event->created());
        $this->assertSame('', $event->message());
        $this->assertSame('info', $event->status());

        $this->assertSame('', $event->parentID());
        $this->assertSame([], $event->parameters());
    }

    public function testProperties()
    {
        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $event = (new JobEvent('abcd'))
            ->withStage('build.created')
            ->withOrder(5)
            ->withCreated($time1)
            ->withMessage('Something happened')
            ->withStatus('Success')
            ->withParentID('1234')
            ->withParameters(['test' => 'value']);

        $this->assertSame('abcd', $event->id());
        $this->assertSame('build.created', $event->stage());
        $this->assertSame(5, $event->order());

        $this->assertSame($time1, $event->created());
        $this->assertSame('Something happened', $event->message());
        $this->assertSame('success', $event->status());

        $this->assertSame('1234', $event->parentID());
        $this->assertSame(['test' => 'value'], $event->parameters());
    }

    public function testSerialization()
    {
        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $event = (new JobEvent('abcd'))
            ->withStage('release.end')
            ->withOrder(5)
            ->withCreated($time1)
            ->withMessage('Something happened')
            ->withStatus('failure')
            ->withParentID('1234')
            ->withParameters(['test' => 'value']);

        $expected = <<<JSON
{
    "id": "abcd",
    "created": "2015-08-15T12:00:00Z",
    "stage": "release.end",
    "status": "failure",
    "order": 5,
    "message": "Something happened",
    "parent_id": "1234",
    "parameters": "**DATA**"
}
JSON;

        $this->assertSame($expected, json_encode($event, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $event = new JobEvent('1', $time);

        $expected = <<<JSON
{
    "id": "1",
    "created": "2015-08-15T12:00:00Z",
    "stage": "unknown",
    "status": "info",
    "order": 0,
    "message": "",
    "parent_id": "",
    "parameters": "**DATA**"
}
JSON;

        $this->assertSame($expected, json_encode($event, JSON_PRETTY_PRINT));
    }

    public function testInvalidStageEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid event stage option.');

        $event = new JobEvent('id');
        $event->withStage('derp');
    }

    public function testInvalidStatusEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"herp" is not a valid status option.');

        $event = new JobEvent('id');
        $event->withStatus('herp');
    }
}
