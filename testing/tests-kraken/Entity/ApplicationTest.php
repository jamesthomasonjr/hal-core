<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Kraken\Core\Entity;

use PHPUnit_Framework_TestCase;
use QL\Hal\Core\Entity\Application as HalApplication;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $application = new Application;

        $this->assertSame('', $application->id());
        $this->assertSame('', $application->name());
        $this->assertSame('', $application->coreId());
        $this->assertSame(null, $application->halApplication());
    }

    public function testProperties()
    {
        $halApp = new HalApplication;

        $application = (new Application)
            ->withId(1234)
            ->withName('My App')
            ->withCoreId(200001)
            ->withHalApplication($halApp);

        $this->assertSame(1234, $application->id());
        $this->assertSame('My App', $application->name());
        $this->assertSame(200001, $application->coreId());
        $this->assertSame($halApp, $application->halApplication());
    }

    public function testSerialization()
    {
        $halApp = (new HalApplication)
            ->withId(56);

        $application = (new Application)
            ->withId(1234)
            ->withName('My App')
            ->withCoreId(200001)
            ->withHalApplication($halApp);


        $expected = <<<JSON
{
    "id": 1234,
    "name": "My App",
    "coreId": 200001,
    "halApplication": 56
}
JSON;

        $this->assertSame($expected, json_encode($application, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $application = new Application;

        $expected = <<<JSON
{
    "id": "",
    "name": "",
    "coreId": "",
    "halApplication": null
}
JSON;

        $this->assertSame($expected, json_encode($application, JSON_PRETTY_PRINT));
    }
}
