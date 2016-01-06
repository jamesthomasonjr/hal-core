<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;
use QL\MCP\Common\Time\TimePoint;

class PushTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $push = new Push;

        $this->assertSame('', $push->id());
        $this->assertSame(null, $push->created());
        $this->assertSame(null, $push->start());
        $this->assertSame(null, $push->end());

        $this->assertSame('Waiting', $push->status());

        $this->assertSame(null, $push->user());
        $this->assertSame(null, $push->build());
        $this->assertSame(null, $push->deployment());
        $this->assertSame(null, $push->application());
        $this->assertCount(0, $push->logs());
    }

    public function testProperties()
    {
        $user = new User;
        $build = new Build;
        $app = new Application;
        $deployment = new Deployment;

        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $time2 = new TimePoint(2014, 8, 15, 12, 0, 0, 'UTC');
        $time3 = new TimePoint(2013, 8, 15, 12, 0, 0, 'UTC');

        $push = (new Push('1234'))
            ->withCreated($time1)
            ->withStart($time2)
            ->withEnd($time3)

            ->withStatus('Pushing')

            ->withUser($user)
            ->withBuild($build)
            ->withApplication($app)
            ->withDeployment($deployment);

        $push->logs()->add(new EventLog);
        $push->logs()->add(new EventLog);

        $this->assertSame('1234', $push->id());
        $this->assertSame($time1, $push->created());
        $this->assertSame($time2, $push->start());
        $this->assertSame($time3, $push->end());

        $this->assertSame('Pushing', $push->status());

        $this->assertSame($user, $push->user());
        $this->assertSame($build, $push->build());
        $this->assertSame($app, $push->application());
        $this->assertSame($deployment, $push->deployment());

        $this->assertCount(2, $push->logs());
    }

    public function testSerialization()
    {

        $env = (new Environment)->withId(9101);

        $user = (new User)->withId(1234);
        $build = new Build('b.1234');
        $app = (new Application)->withId(5678);
        $deployment = (new Deployment)->withId(9101);

        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $time2 = new TimePoint(2014, 8, 15, 12, 0, 0, 'UTC');
        $time3 = new TimePoint(2013, 8, 15, 12, 0, 0, 'UTC');

        $push = (new Push('1234'))
            ->withCreated($time1)
            ->withStart($time2)
            ->withEnd($time3)

            ->withStatus('Pushing')

            ->withUser($user)
            ->withBuild($build)
            ->withApplication($app)
            ->withDeployment($deployment);

        $expected = <<<JSON
{
    "id": "1234",
    "created": "2015-08-15T12:00:00Z",
    "start": "2014-08-15T12:00:00Z",
    "end": "2013-08-15T12:00:00Z",
    "status": "Pushing",
    "user": 1234,
    "build": "b.1234",
    "deployment": 9101,
    "application": 5678
}
JSON;

        $this->assertSame($expected, json_encode($push, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $push = new Push;

        $expected = <<<JSON
{
    "id": "",
    "created": null,
    "start": null,
    "end": null,
    "status": "Waiting",
    "user": null,
    "build": null,
    "deployment": null,
    "application": null
}
JSON;

        $this->assertSame($expected, json_encode($push, JSON_PRETTY_PRINT));
    }
}
