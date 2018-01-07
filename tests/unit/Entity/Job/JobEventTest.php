<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\Job;

use Hal\Core\Entity\Job;
use Hal\Core\Entity\JobType\Build;
use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class JobEventTest extends TestCase
{
    public function testDefaultValues()
    {
        $event = new JobEvent;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $event->id());
        $this->assertInstanceOf(TimePoint::class, $event->created());

        $this->assertSame('unknown', $event->stage());
        $this->assertSame(0, $event->order());
        $this->assertSame('', $event->message());
        $this->assertSame('info', $event->status());

        $this->assertSame([], $event->parameters());
    }

    public function testProperties()
    {
        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $event = (new JobEvent('abcd', $time1))
            ->withStage('build.created')
            ->withOrder(5)
            ->withMessage('Something happened')
            ->withStatus('Success')
            ->withJob(new Build('1234'))
            ->withParameters(['test' => 'value']);

        $this->assertSame('abcd', $event->id());
        $this->assertSame('build.created', $event->stage());
        $this->assertSame(5, $event->order());

        $this->assertSame($time1, $event->created());
        $this->assertSame('Something happened', $event->message());
        $this->assertSame('success', $event->status());

        $this->assertSame('1234', $event->job()->id());
        $this->assertSame(['test' => 'value'], $event->parameters());
    }

    public function testSerialization()
    {
        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $event = (new JobEvent('abcd', $time1))
            ->withStage('release.end')
            ->withOrder(5)
            ->withMessage('Something happened')
            ->withStatus('failure')
            ->withJob(new Job('5678'))
            ->withParameters(['test' => 'value']);

        $expected = <<<JSON_TEXT
{
    "id": "abcd",
    "created": "2015-08-15T12:00:00Z",
    "stage": "release.end",
    "status": "failure",
    "order": 5,
    "message": "Something happened",
    "parameters": "**DATA**",
    "job_id": "5678"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($event, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $event = new JobEvent('1', $time);
        $event->withJob(new Job('1234'));


        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2015-08-15T12:00:00Z",
    "stage": "unknown",
    "status": "info",
    "order": 0,
    "message": "",
    "parameters": "**DATA**",
    "job_id": "1234"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($event, JSON_PRETTY_PRINT));
    }

    public function testInvalidStageEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid JobEventStageEnum option.');

        $event = new JobEvent('id');
        $event->withStage('derp');
    }

    public function testInvalidStatusEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"herp" is not a valid JobEventStatusEnum option.');

        $event = new JobEvent('id');
        $event->withStatus('herp');
    }
}
