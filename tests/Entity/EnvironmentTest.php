<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class EnvironmentTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $environment = new Environment;

        $this->assertSame(null, $environment->id());
        $this->assertSame('', $environment->name());
        $this->assertSame(false, $environment->isProduction());
    }

    public function testProperties()
    {
        $environment = (new Environment)
            ->withId(1234)
            ->withName('env')
            ->withIsProduction(true);

        $this->assertSame(1234, $environment->id());
        $this->assertSame('env', $environment->name());
        $this->assertSame(true, $environment->isProduction());
    }

    public function testSerialization()
    {
        $environment = (new Environment)
            ->withId(5678)
            ->withName('env-test')
            ->withIsProduction(true);

        $expected = <<<JSON
{
    "id": 5678,
    "name": "env-test",
    "isProduction": true
}
JSON;

        $this->assertSame($expected, json_encode($environment, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $environment = new Environment;

        $expected = <<<JSON
{
    "id": null,
    "name": "",
    "isProduction": false
}
JSON;

        $this->assertSame($expected, json_encode($environment, JSON_PRETTY_PRINT));
    }
}
