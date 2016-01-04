<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use MCP\DataType\Time\TimePoint;
use PHPUnit_Framework_TestCase;

class BuildTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $build = new Build;

        $this->assertSame('', $build->id());
        $this->assertSame(null, $build->created());
        $this->assertSame(null, $build->start());
        $this->assertSame(null, $build->end());

        $this->assertSame(null, $build->status());
        $this->assertSame('', $build->branch());
        $this->assertSame('', $build->commit());

        $this->assertSame(null, $build->user());
        $this->assertSame(null, $build->application());
        $this->assertSame(null, $build->environment());
        $this->assertCount(0, $build->logs());
    }

    public function testProperties()
    {
        $user = new User;
        $app = new Application;
        $env = new Environment;

        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $time2 = new TimePoint(2014, 8, 15, 12, 0, 0, 'UTC');
        $time3 = new TimePoint(2013, 8, 15, 12, 0, 0, 'UTC');

        $build = (new Build('1234'))
            ->withCreated($time1)
            ->withStart($time2)
            ->withEnd($time3)

            ->withStatus('Waiting')
            ->withBranch('mybranch')
            ->withCommit('abcdef123456')

            ->withUser($user)
            ->withApplication($app)
            ->withEnvironment($env);

        $build->logs()->add(new EventLog);
        $build->logs()->add(new EventLog);

        $this->assertSame('1234', $build->id());
        $this->assertSame($time1, $build->created());
        $this->assertSame($time2, $build->start());
        $this->assertSame($time3, $build->end());

        $this->assertSame('Waiting', $build->status());
        $this->assertSame('mybranch', $build->branch());
        $this->assertSame('abcdef123456', $build->commit());

        $this->assertSame($user, $build->user());
        $this->assertSame($app, $build->application());
        $this->assertSame($env, $build->environment());

        $this->assertCount(2, $build->logs());
    }

    public function testSerialization()
    {
        $user = (new User)->withId(1234);
        $app = (new Application)->withId(5678);
        $env = (new Environment)->withId(9101);

        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $time2 = new TimePoint(2014, 8, 15, 12, 0, 0, 'UTC');
        $time3 = new TimePoint(2013, 8, 15, 12, 0, 0, 'UTC');

        $build = (new Build('1234'))
            ->withCreated($time1)
            ->withStart($time2)
            ->withEnd($time3)

            ->withStatus('Waiting')
            ->withBranch('mybranch')
            ->withCommit('abcdef123456')

            ->withUser($user)
            ->withApplication($app)
            ->withEnvironment($env);

        $expected = <<<JSON
{
    "id": "1234",
    "created": "2015-08-15T12:00:00Z",
    "start": "2014-08-15T12:00:00Z",
    "end": "2013-08-15T12:00:00Z",
    "status": "Waiting",
    "branch": "mybranch",
    "commit": "abcdef123456",
    "user": 1234,
    "repository": 5678,
    "environment": 9101
}
JSON;

        $this->assertSame($expected, json_encode($build, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $build = new Build;

        $expected = <<<JSON
{
    "id": "",
    "created": null,
    "start": null,
    "end": null,
    "status": null,
    "branch": "",
    "commit": "",
    "user": null,
    "repository": null,
    "environment": null
}
JSON;

        $this->assertSame($expected, json_encode($build, JSON_PRETTY_PRINT));
    }
}
