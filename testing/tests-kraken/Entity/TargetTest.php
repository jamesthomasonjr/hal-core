<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Kraken\Core\Entity;

use PHPUnit_Framework_TestCase;

class TargetTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $target = new Target;

        $this->assertSame('', $target->id());
        $this->assertSame('', $target->key());

        $this->assertSame(null, $target->application());
        $this->assertSame(null, $target->environment());
        $this->assertSame(null, $target->configuration());
    }

    public function testProperties()
    {
        $application = new Application;
        $environment = new Environment;
        $configuration = new Configuration;

        $target = (new Target('abcd'))
            ->withId('abcdef')
            ->withKey('property.key')

            ->withApplication($application)
            ->withEnvironment($environment)
            ->withConfiguration($configuration);

        $this->assertSame('abcdef', $target->id());
        $this->assertSame('property.key', $target->key());

        $this->assertSame($application, $target->application());
        $this->assertSame($configuration, $target->configuration());
        $this->assertSame($environment, $target->environment());
    }

    public function testSerialization()
    {
        $application = new Application('ab');
        $environment = new Environment('cd');
        $configuration = new Configuration('ef');

        $target = (new Target('abcd'))
            ->withId('abcdef')
            ->withKey('property.key')

            ->withApplication($application)
            ->withEnvironment($environment)
            ->withConfiguration($configuration);

        $expected = <<<JSON
{
    "id": "abcdef",
    "key": "property.key",
    "application": "ab",
    "environment": "cd",
    "configuration": "ef"
}
JSON;

        $this->assertSame($expected, json_encode($target, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $target = new Target;

        $expected = <<<JSON
{
    "id": "",
    "key": "",
    "application": null,
    "environment": null,
    "configuration": null
}
JSON;

        $this->assertSame($expected, json_encode($target, JSON_PRETTY_PRINT));
    }
}
