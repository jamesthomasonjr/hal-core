<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class EncryptedPropertyTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $property = new EncryptedProperty;

        $this->assertSame('', $property->id());
        $this->assertSame('', $property->name());
        $this->assertSame('', $property->data());

        $this->assertSame(null, $property->application());
        $this->assertSame(null, $property->environment());
    }

    public function testProperties()
    {
        $application = new Application;
        $environment = new Environment;

        $property = (new EncryptedProperty('abcdef'))
            ->withName('property_name')
            ->withData('some data here')
            ->withApplication($application)
            ->withEnvironment($environment);

        $this->assertSame('abcdef', $property->id());
        $this->assertSame('property_name', $property->name());
        $this->assertSame('some data here', $property->data());

        $this->assertSame($application, $property->application());
        $this->assertSame($environment, $property->environment());
    }

    public function testSerialization()
    {
        $application = (new Application)->withId(1234);
        $environment = (new Environment)->withId(5678);

        $property = (new EncryptedProperty('abcdef'))
            ->withName('property_name')
            ->withData('some data here')
            ->withApplication($application)
            ->withEnvironment($environment);

        $expected = <<<JSON
{
    "id": "abcdef",
    "name": "property_name",
    "data": "**ENCRYPTED**",
    "application": 1234,
    "environment": 5678
}
JSON;

        $this->assertSame($expected, json_encode($property, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $property = new EncryptedProperty;

        $expected = <<<JSON
{
    "id": "",
    "name": "",
    "data": "**ENCRYPTED**",
    "application": null,
    "environment": null
}
JSON;

        $this->assertSame($expected, json_encode($property, JSON_PRETTY_PRINT));
    }
}
