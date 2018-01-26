<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\JobType;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Target;
use Hal\Core\Entity\User;
use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class ReleaseTest extends TestCase
{
    public function testDefaultValues()
    {
        $release = new Release;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $release->id());
        $this->assertInstanceOf(TimePoint::class, $release->created());

        $this->assertSame(null, $release->start());
        $this->assertSame(null, $release->end());

        $this->assertSame('pending', $release->status());

        $this->assertSame(null, $release->user());
        // $this->assertSame(null, $release->build());
        $this->assertSame(null, $release->target());
        $this->assertSame(null, $release->application());
    }

    public function testProperties()
    {
        $user = new User;
        $build = new Build;
        $app = new Application;
        $target = new Target;

        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $time2 = new TimePoint(2014, 8, 15, 12, 0, 0, 'UTC');
        $time3 = new TimePoint(2013, 8, 15, 12, 0, 0, 'UTC');

        $release = (new Release('1234', $time1))
            ->withStart($time2)
            ->withEnd($time3)

            ->withStatus('running')

            ->withUser($user)
            ->withBuild($build)
            ->withApplication($app)
            ->withTarget($target);

        $this->assertSame('1234', $release->id());
        $this->assertSame($time1, $release->created());
        $this->assertSame($time2, $release->start());
        $this->assertSame($time3, $release->end());

        $this->assertSame('running', $release->status());

        $this->assertSame($user, $release->user());
        $this->assertSame($build, $release->build());
        $this->assertSame($app, $release->application());
        $this->assertSame($target, $release->target());
    }

    public function testSerialization()
    {
        $env = new Environment('1112');

        $user = new User('1234');
        $build = new Build('b1234');
        $app = new Application('5678');
        $target = new Target('rsync', '9101');

        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $time2 = new TimePoint(2014, 8, 15, 12, 0, 0, 'UTC');
        $time3 = new TimePoint(2013, 8, 15, 12, 0, 0, 'UTC');

        $release = (new Release('1234', $time1))
            ->withStart($time2)
            ->withEnd($time3)

            ->withStatus('running')
            ->withParameter('test', 'value1')
            ->withParameter('my.test', 'value2')

            ->withUser($user)
            ->withBuild($build)
            ->withApplication($app)
            ->withTarget($target);

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
    "meta": [],
    "build_id": "b1234",
    "application_id": "5678",
    "environment_id": null,
    "target_id": "9101"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($release, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $release = new Release('1', $time);
        $release->withBuild(new Build('1234'));

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2015-08-15T12:00:00Z",
    "type": "release",
    "status": "pending",
    "parameters": [],
    "start": null,
    "end": null,
    "user_id": null,
    "artifacts": [],
    "events": [],
    "meta": [],
    "build_id": "1234",
    "application_id": null,
    "environment_id": null,
    "target_id": null
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($release, JSON_PRETTY_PRINT));
    }

    public function testConvenienceMethods()
    {
        $release = new Release('id');

        $release->withStatus('scheduled');
        $this->assertSame(false, $release->inProgress());
        $this->assertSame(false, $release->isFinished());
        $this->assertSame(false, $release->isSuccess());
        $this->assertSame(false, $release->isFailure());

        $release->withStatus('pending');
        $this->assertSame(true, $release->inProgress());
        $this->assertSame(false, $release->isFinished());
        $this->assertSame(false, $release->isSuccess());
        $this->assertSame(false, $release->isFailure());

        $release->withStatus('running');
        $this->assertSame(true, $release->inProgress());
        $this->assertSame(false, $release->isFinished());
        $this->assertSame(false, $release->isSuccess());
        $this->assertSame(false, $release->isFailure());

        $release->withStatus('success');
        $this->assertSame(false, $release->inProgress());
        $this->assertSame(true, $release->isFinished());
        $this->assertSame(true, $release->isSuccess());
        $this->assertSame(false, $release->isFailure());

        $release->withStatus('failure');
        $this->assertSame(false, $release->inProgress());
        $this->assertSame(true, $release->isFinished());
        $this->assertSame(false, $release->isSuccess());
        $this->assertSame(true, $release->isFailure());
    }

    public function testInvalidEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid JobStatusEnum option.');

        $release = new Release('id');
        $release->withStatus('derp');
    }
}
