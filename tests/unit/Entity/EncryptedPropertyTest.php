<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class EncryptedPropertyTest extends TestCase
{
    public function testDefaultValues()
    {
        $property = new EncryptedProperty;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $property->id());
        $this->assertSame('', $property->name());
        $this->assertSame('', $property->secret());

        $this->assertSame(null, $property->application());
        $this->assertSame(null, $property->organization());
        $this->assertSame(null, $property->environment());
    }

    public function testProperties()
    {
        $application = new Application;
        $environment = new Environment;

        $property = (new EncryptedProperty('abcdef'))
            ->withName('property_name')
            ->withSecret('some data here')
            ->withApplication($application)
            ->withEnvironment($environment);

        $this->assertSame('abcdef', $property->id());
        $this->assertSame('property_name', $property->name());
        $this->assertSame('some data here', $property->secret());

        $this->assertSame($application, $property->application());
        $this->assertSame($environment, $property->environment());
    }

    public function testSerialization()
    {
        $application = new Application('1234');
        $environment = new Environment('5678');

        $property = (new EncryptedProperty('abcdef', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC')))
            ->withName('property_name')
            ->withSecret('some data here')
            ->withApplication($application)
            ->withEnvironment($environment);

        $expected = <<<JSON_TEXT
{
    "id": "abcdef",
    "created": "2018-01-01T12:00:00Z",
    "name": "property_name",
    "secret": "**ENCRYPTED**",
    "application_id": "1234",
    "organization_id": null,
    "environment_id": "5678"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($property, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $property = new EncryptedProperty('1', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC'));

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2018-01-01T12:00:00Z",
    "name": "",
    "secret": "**ENCRYPTED**",
    "application_id": null,
    "organization_id": null,
    "environment_id": null
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($property, JSON_PRETTY_PRINT));
    }
}
