<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Entity\JobType\Build;
use Hal\Core\Entity\JobType\Release;
use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class ScheduledActionTest extends TestCase
{
    public function testDefaultValues()
    {
        $scheduled = new ScheduledAction;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $scheduled->id());
        $this->assertInstanceOf(TimePoint::class, $scheduled->created());

        $this->assertSame(null, $scheduled->user());
        $this->assertSame(null, $scheduled->triggerJob());
        $this->assertSame(null, $scheduled->scheduledJob());

        $this->assertSame('pending', $scheduled->status());
        $this->assertSame('', $scheduled->message());
        $this->assertSame([], $scheduled->parameters());
    }

    public function testProperties()
    {
        $user = new User;
        $build = new Build('abcd');
        $release = new Release('efgh');

        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $scheduled = (new ScheduledAction('1234', $time))
            ->withUser($user)

            ->withStatus('Aborted')
            ->withMessage('test message')
            ->withParameters([
                'test1' => 'abcdef',
                'test2' => '123456'
            ])

            ->withTriggerJob($build)
            ->withScheduledJob($release);

        $this->assertSame('1234', $scheduled->id());
        $this->assertSame($time, $scheduled->created());
        $this->assertSame($user, $scheduled->user());

        $this->assertSame('aborted', $scheduled->status());
        $this->assertSame('test message', $scheduled->message());
        $this->assertSame([
                'test1' => 'abcdef',
                'test2' => '123456'
            ], $scheduled->parameters());

        $this->assertSame($build, $scheduled->triggerJob());
        $this->assertSame($release, $scheduled->scheduledJob());
    }

    public function testSerialization()
    {
        $user = new User('456');
        $build = new Build('abcd');
        $release = new Release('efgh');

        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $scheduled = (new ScheduledAction('1234', $time))
            ->withUser($user)

            ->withStatus('Aborted')
            ->withMessage('test message')
            ->withParameters([
                'test1' => 'abcdef',
                'test2' => '123456'
            ])

            ->withTriggerJob($build)
            ->withScheduledJob($release);

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2015-08-15T12:00:00Z",
    "status": "aborted",
    "message": "test message",
    "parameters": {
        "test1": "abcdef",
        "test2": "123456"
    },
    "trigger_job_id": "abcd",
    "scheduled_job_id": "efgh",
    "user_id": "456"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($scheduled, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $scheduled = new ScheduledAction('1', $time);

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2015-08-15T12:00:00Z",
    "status": "pending",
    "message": "",
    "parameters": [],
    "trigger_job_id": null,
    "scheduled_job_id": null,
    "user_id": null
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($scheduled, JSON_PRETTY_PRINT));
    }

    public function testInvalidEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid ScheduledActionStatusEnum option.');

        $scheduled = new ScheduledAction('id');
        $scheduled->withStatus('derp');
    }
}
