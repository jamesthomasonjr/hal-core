<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Entity;

use PHPUnit_Framework_TestCase;
use QL\Hal\Core\Entity\User;
use QL\MCP\Common\Time\TimePoint;

class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $config = new Configuration;

        $this->assertSame('', $config->id());
        $this->assertSame(false, $config->isSuccess());
        $this->assertSame('', $config->audit());

        $this->assertSame(null, $config->created());
        $this->assertSame(null, $config->application());
        $this->assertSame(null, $config->environment());
        $this->assertSame(null, $config->user());
    }

    public function testProperties()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $user = new User;
        $application = new Application;
        $environment = new Environment;

        $config = (new Configuration('abcd'))
            ->withId('abcdef')
            ->withIsSuccess(true)
            ->withAudit('audit-data')
            ->withCreated($time)
            ->withUser($user)
            ->withApplication($application)
            ->withEnvironment($environment);

        $this->assertSame('abcdef', $config->id());
        $this->assertSame(true, $config->isSuccess());
        $this->assertSame('audit-data', $config->audit());

        $this->assertSame($time, $config->created());
        $this->assertSame($application, $config->application());
        $this->assertSame($environment, $config->environment());
        $this->assertSame($user, $config->user());
    }

    public function testSerialization()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $user = new User(1234);
        $application = new Application('ed');
        $environment = new Environment('fg');

        $config = (new Configuration('abcd'))
            ->withId('abcdef')
            ->withIsSuccess(true)
            ->withAudit('audit-data')
            ->withCreated($time)
            ->withUser($user)
            ->withApplication($application)
            ->withEnvironment($environment);

        $expected = <<<JSON
{
    "id": "abcdef",
    "isSuccess": true,
    "audit": "audit-data",
    "created": "2015-08-15T12:00:00Z",
    "application": "ed",
    "environment": "fg",
    "user": 1234
}
JSON;

        $this->assertSame($expected, json_encode($config, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $config = new Configuration;

        $expected = <<<JSON
{
    "id": "",
    "isSuccess": false,
    "audit": "",
    "created": null,
    "application": null,
    "environment": null,
    "user": null
}
JSON;

        $this->assertSame($expected, json_encode($config, JSON_PRETTY_PRINT));
    }
}
