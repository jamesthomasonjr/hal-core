<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Entity\User;
use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class JobTest extends TestCase
{
    public function testDefaultValues()
    {
        $job = new Job;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $job->id());
        $this->assertInstanceOf(TimePoint::class, $job->created());

        $this->assertSame(null, $job->start());
        $this->assertSame(null, $job->end());

        $this->assertSame('build', $job->type());
        $this->assertSame('pending', $job->status());

        $this->assertSame([], $job->artifacts()->toArray());
        $this->assertSame([], $job->events()->toArray());
        $this->assertSame([], $job->meta()->toArray());

        $this->assertSame([], $job->parameters());

        $this->assertSame(null, $job->user());
    }

    public function testProperties()
    {
        $user = new User;

        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $time2 = new TimePoint(2014, 8, 15, 12, 0, 0, 'UTC');
        $time3 = new TimePoint(2013, 8, 15, 12, 0, 0, 'UTC');

        $job = (new Job('build', '1234', $time1))
            ->withStart($time2)
            ->withEnd($time3)

            ->withStatus('running')
            ->withParameter('this', 'that')

            ->withUser($user);

        $this->assertSame('1234', $job->id());
        $this->assertSame($time1, $job->created());
        $this->assertSame($time2, $job->start());
        $this->assertSame($time3, $job->end());

        $this->assertSame('build', $job->type());
        $this->assertSame('running', $job->status());
        $this->assertSame('that', $job->parameter('this'));

        $this->assertSame($user, $job->user());
    }

    public function testSerialization()
    {
        $env = new Environment('1112');

        $user = new User('1234');

        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $time2 = new TimePoint(2014, 8, 15, 12, 0, 0, 'UTC');
        $time3 = new TimePoint(2013, 8, 15, 12, 0, 0, 'UTC');

        $job = (new Job('release', '1234', $time1))
            ->withStart($time2)
            ->withEnd($time3)

            ->withStatus('running')
            ->withParameter('test', 'value1')
            ->withParameter('my.test', 'value2')

            ->withUser($user);

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2015-08-15T12:00:00Z",
    "type": "release",
    "status": "running",
    "parameters": {
        "test": "value1",
        "my.test": "value2"
    },
    "start": "2014-08-15T12:00:00Z",
    "end": "2013-08-15T12:00:00Z",
    "user_id": "1234",
    "artifacts": [],
    "events": [],
    "meta": []
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($job, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $job = new Job('release', '1234', $time);

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2015-08-15T12:00:00Z",
    "type": "release",
    "status": "pending",
    "parameters": [],
    "start": null,
    "end": null,
    "user_id": null,
    "artifacts": [],
    "events": [],
    "meta": []
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($job, JSON_PRETTY_PRINT));
    }

    public function testConvenienceMethods()
    {
        $job = new Job;

        $job->withStatus('scheduled');
        $this->assertSame(false, $job->inProgress());
        $this->assertSame(false, $job->isFinished());
        $this->assertSame(false, $job->isSuccess());
        $this->assertSame(false, $job->isFailure());

        $job->withStatus('pending');
        $this->assertSame(true, $job->inProgress());
        $this->assertSame(false, $job->isFinished());
        $this->assertSame(false, $job->isSuccess());
        $this->assertSame(false, $job->isFailure());

        $job->withStatus('running');
        $this->assertSame(true, $job->inProgress());
        $this->assertSame(false, $job->isFinished());
        $this->assertSame(false, $job->isSuccess());
        $this->assertSame(false, $job->isFailure());

        $job->withStatus('success');
        $this->assertSame(false, $job->inProgress());
        $this->assertSame(true, $job->isFinished());
        $this->assertSame(true, $job->isSuccess());
        $this->assertSame(false, $job->isFailure());

        $job->withStatus('failure');
        $this->assertSame(false, $job->inProgress());
        $this->assertSame(true, $job->isFinished());
        $this->assertSame(false, $job->isSuccess());
        $this->assertSame(true, $job->isFailure());
    }

    public function testInvalidTypeEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid JobEnum option.');

        $job = new Job('derp');
    }

    public function testInvalidStatusEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid JobStatusEnum option.');

        $job = new Job;
        $job->withStatus('derp');
    }
}
