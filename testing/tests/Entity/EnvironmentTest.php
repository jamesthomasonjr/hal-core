<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class EnvironmentTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $environment = new Environment;

        $this->assertStringMatchesFormat('%x', $environment->id());
        $this->assertSame('', $environment->name());
        $this->assertSame(false, $environment->isProduction());
    }

    public function testProperties()
    {
        $environment = (new Environment)
            ->withID('1234')
            ->withName('env')
            ->withIsProduction(true);

        $this->assertSame('1234', $environment->id());
        $this->assertSame('env', $environment->name());
        $this->assertSame(true, $environment->isProduction());
    }

    public function testSerialization()
    {
        $environment = (new Environment('5678'))
            ->withName('env-test')
            ->withIsProduction(true);

        $expected = <<<JSON
{
    "id": "5678",
    "name": "env-test",
    "is_production": true
}
JSON;

        $this->assertSame($expected, json_encode($environment, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $environment = new Environment('1');

        $expected = <<<JSON
{
    "id": "1",
    "name": "",
    "is_production": false
}
JSON;

        $this->assertSame($expected, json_encode($environment, JSON_PRETTY_PRINT));
    }
}
