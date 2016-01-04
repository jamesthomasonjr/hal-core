<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class ServerTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $push = new Server;

        $this->assertSame(null, $push->id());
        $this->assertSame('', $push->type());
        $this->assertSame('', $push->name());

        $this->assertSame(null, $push->environment());
        $this->assertCount(0, $push->deployments());
    }

    public function testProperties()
    {
        $environment = new Environment;

        $server = (new Server)
            ->withId(1234)
            ->withType('rsync')
            ->withName('hostname')
            ->withEnvironment($environment);

        $server->deployments()->add(new Deployment);
        $server->deployments()->add(new Deployment);

        $this->assertSame(1234, $server->id());
        $this->assertSame('rsync', $server->type());
        $this->assertSame('hostname', $server->name());

        $this->assertCount(2, $server->deployments());
    }

    public function testSerialization()
    {
        $environment = (new Environment)->withId(9101);

        $server = (new Server)
            ->withId(1234)
            ->withType('rsync')
            ->withName('hostname')
            ->withEnvironment($environment);

        $expected = <<<JSON
{
    "id": 1234,
    "type": "rsync",
    "name": "hostname",
    "environment": 9101
}
JSON;

        $this->assertSame($expected, json_encode($server, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $server = new Server;

        $expected = <<<JSON
{
    "id": null,
    "type": "",
    "name": "",
    "environment": null
}
JSON;

        $this->assertSame($expected, json_encode($server, JSON_PRETTY_PRINT));
    }
}
