<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class EnvironmentTest extends TestCase
{
    public function testDefaultValues()
    {
        $environment = new Environment;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $environment->id());
        $this->assertInstanceOf(TimePoint::class, $environment->created());

        $this->assertSame('', $environment->name());
        $this->assertSame(false, $environment->isProduction());
    }

    public function testProperties()
    {
        $environment = (new Environment('1234'))
            ->withName('env')
            ->withIsProduction(true);

        $this->assertSame('1234', $environment->id());
        $this->assertSame('env', $environment->name());
        $this->assertSame(true, $environment->isProduction());
    }

    public function testSerialization()
    {
        $environment = (new Environment('5678', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC')))
            ->withName('env-test')
            ->withIsProduction(true);

        $expected = <<<JSON_TEXT
{
    "id": "5678",
    "created": "2018-01-01T12:00:00Z",
    "name": "env-test",
    "is_production": true
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($environment, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $environment = new Environment('1', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC'));

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2018-01-01T12:00:00Z",
    "name": "",
    "is_production": false
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($environment, JSON_PRETTY_PRINT));
    }
}
