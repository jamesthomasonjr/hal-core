<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class EncryptedPropertyTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $property = new EncryptedProperty;

        $this->assertStringMatchesFormat('%x', $property->id());
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
        $application = new Application('1234');
        $environment = new Environment('5678');

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
    "application_id": "1234",
    "environment_id": "5678"
}
JSON;

        $this->assertSame($expected, json_encode($property, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $property = new EncryptedProperty('1');

        $expected = <<<JSON
{
    "id": "1",
    "name": "",
    "data": "**ENCRYPTED**",
    "application_id": null,
    "environment_id": null
}
JSON;

        $this->assertSame($expected, json_encode($property, JSON_PRETTY_PRINT));
    }
}
