<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\JobType;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\User;
use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class BuildTest extends TestCase
{
    public function testDefaultValues()
    {
        $build = new Build;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $build->id());
        $this->assertInstanceOf(TimePoint::class, $build->created());

        $this->assertSame(null, $build->start());
        $this->assertSame(null, $build->end());

        $this->assertSame('pending', $build->status());
        $this->assertSame('', $build->reference());
        $this->assertSame('', $build->commit());

        $this->assertSame(null, $build->user());
        $this->assertSame(null, $build->application());
        $this->assertSame(null, $build->environment());

        $this->assertSame(true, $build->inProgress());
        $this->assertSame(false, $build->isFinished());
        $this->assertSame(false, $build->isSuccess());
        $this->assertSame(false, $build->isFailure());
    }

    public function testProperties()
    {
        $user = new User;
        $app = new Application;
        $env = new Environment;

        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $time2 = new TimePoint(2014, 8, 15, 12, 0, 0, 'UTC');
        $time3 = new TimePoint(2013, 8, 15, 12, 0, 0, 'UTC');

        $build = (new Build('1234', $time1))
            ->withStart($time2)
            ->withEnd($time3)

            ->withStatus('success')
            ->withReference('mybranch')
            ->withCommit('abcdef123456')

            ->withUser($user)
            ->withApplication($app)
            ->withEnvironment($env);

        $this->assertSame('1234', $build->id());
        $this->assertSame($time1, $build->created());
        $this->assertSame($time2, $build->start());
        $this->assertSame($time3, $build->end());

        $this->assertSame('success', $build->status());
        $this->assertSame('mybranch', $build->reference());
        $this->assertSame('abcdef123456', $build->commit());

        $this->assertSame($user, $build->user());
        $this->assertSame($app, $build->application());
        $this->assertSame($env, $build->environment());

        $this->assertSame(false, $build->inProgress());
        $this->assertSame(true, $build->isFinished());
        $this->assertSame(true, $build->isSuccess());
        $this->assertSame(false, $build->isFailure());
    }

    public function testSerialization()
    {
        $user = new User('1234');
        $app = new Application('5678');
        $env = new Environment('9101');

        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $time2 = new TimePoint(2014, 8, 15, 12, 0, 0, 'UTC');
        $time3 = new TimePoint(2013, 8, 15, 12, 0, 0, 'UTC');

        $build = (new Build('1234', $time1))
            ->withStart($time2)
            ->withEnd($time3)

            ->withStatus('Failure')
            ->withReference('mybranch')
            ->withCommit('abcdef123456')

            ->withUser($user)
            ->withApplication($app)
            ->withEnvironment($env);

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2015-08-15T12:00:00Z",
    "type": "build",
    "status": "failure",
    "parameters": [],
    "start": "2014-08-15T12:00:00Z",
    "end": "2013-08-15T12:00:00Z",
    "user_id": "1234",
    "artifacts": [],
    "events": [],
    "meta": [],
    "reference": "mybranch",
    "commit": "abcdef123456",
    "application_id": "5678",
    "environment_id": "9101"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($build, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $time1 = new TimePoint(2017, 1, 25, 12, 0, 0, 'UTC');

        $build = new Build('1', $time1);

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2017-01-25T12:00:00Z",
    "type": "build",
    "status": "pending",
    "parameters": [],
    "start": null,
    "end": null,
    "user_id": null,
    "artifacts": [],
    "events": [],
    "meta": [],
    "reference": "",
    "commit": "",
    "application_id": null,
    "environment_id": null
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($build, JSON_PRETTY_PRINT));
    }


    public function testConvenienceMethods()
    {
        $build = new Build('id');

        $build->withStatus('pending');
        $this->assertSame(true, $build->inProgress());
        $this->assertSame(false, $build->isFinished());
        $this->assertSame(false, $build->isSuccess());
        $this->assertSame(false, $build->isFailure());

        $build->withStatus('running');
        $this->assertSame(true, $build->inProgress());
        $this->assertSame(false, $build->isFinished());
        $this->assertSame(false, $build->isSuccess());
        $this->assertSame(false, $build->isFailure());

        $build->withStatus('success');
        $this->assertSame(false, $build->inProgress());
        $this->assertSame(true, $build->isFinished());
        $this->assertSame(true, $build->isSuccess());
        $this->assertSame(false, $build->isFailure());

        $build->withStatus('failure');
        $this->assertSame(false, $build->inProgress());
        $this->assertSame(true, $build->isFinished());
        $this->assertSame(false, $build->isSuccess());
        $this->assertSame(true, $build->isFailure());
    }

    public function testInvalidEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid JobStatusEnum option.');

        $build = new Build('id');
        $build->withStatus('derp');
    }
}
