<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Entity;

use MCP\DataType\Time\TimePoint;
use PHPUnit_Framework_TestCase;

class EventLogTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $log = new EventLog;

        $this->assertSame('', $log->id());
        $this->assertSame('', $log->event());
        $this->assertSame(0, $log->order());
        $this->assertSame(null, $log->created());
        $this->assertSame('', $log->message());
        $this->assertSame('', $log->status());

        $this->assertSame(null, $log->build());
        $this->assertSame(null, $log->push());
        $this->assertSame([], $log->data());
    }

    public function testProperties()
    {
        $build = new Build;
        $push = new Push;
        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $log = (new EventLog('abcd'))
            ->withEvent('event.name')
            ->withOrder(5)
            ->withCreated($time1)
            ->withMessage('Something happened')
            ->withStatus('Success')
            ->withBuild($build)
            ->withPush($push)
            ->withData(['test' => 'value']);

        $this->assertSame('abcd', $log->id());
        $this->assertSame('event.name', $log->event());
        $this->assertSame(5, $log->order());

        $this->assertSame($time1, $log->created());
        $this->assertSame('Something happened', $log->message());
        $this->assertSame('Success', $log->status());

        $this->assertSame($build, $log->build());
        $this->assertSame($push, $log->push());
        $this->assertSame(['test' => 'value'], $log->data());
    }

    public function testSerialization()
    {
        $build = new Build('b.abcd');
        $push = new Push('p.abcd');
        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $log = (new EventLog('abcd'))
            ->withEvent('event.name')
            ->withOrder(5)
            ->withCreated($time1)
            ->withMessage('Something happened')
            ->withStatus('Success')
            ->withBuild($build)
            ->withPush($push)
            ->withData(['test' => 'value']);

        $expected = <<<JSON
{
    "id": "abcd",
    "created": "2015-08-15T12:00:00Z",
    "event": "event.name",
    "order": 5,
    "message": "Something happened",
    "status": "Success",
    "build": "b.abcd",
    "push": "p.abcd",
    "data": "**DATA**"
}
JSON;

        $this->assertSame($expected, json_encode($log, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $log = new EventLog;

        $expected = <<<JSON
{
    "id": "",
    "created": null,
    "event": "",
    "order": 0,
    "message": "",
    "status": "",
    "build": null,
    "push": null,
    "data": "**DATA**"
}
JSON;

        $this->assertSame($expected, json_encode($log, JSON_PRETTY_PRINT));
    }
}
