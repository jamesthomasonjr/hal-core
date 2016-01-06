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

class PropertyTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $property = new Property;

        $this->assertSame('', $property->id());
        $this->assertSame('', $property->value());

        $this->assertSame(null, $property->created());
        $this->assertSame(null, $property->schema());
        $this->assertSame(null, $property->application());
        $this->assertSame(null, $property->environment());
        $this->assertSame(null, $property->user());
    }

    public function testProperties()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $user = new User;
        $schema = new Schema;
        $application = new Application;
        $environment = new Environment;

        $property = (new Property('abcd'))
            ->withId('abcdef')
            ->withValue('property_value')

            ->withCreated($time)
            ->withSchema($schema)
            ->withApplication($application)
            ->withEnvironment($environment)
            ->withUser($user);

        $this->assertSame('abcdef', $property->id());
        $this->assertSame('property_value', $property->value());

        $this->assertSame($time, $property->created());
        $this->assertSame($schema, $property->schema());
        $this->assertSame($application, $property->application());
        $this->assertSame($environment, $property->environment());
        $this->assertSame($user, $property->user());
    }

    public function testSerialization()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $user = new User(1234);
        $schema = new Schema('ab');
        $application = new Application('cd');
        $environment = new Environment('ef');

        $property = (new Property('abcd'))
            ->withId('abcdef')
            ->withValue('property_value')

            ->withCreated($time)
            ->withSchema($schema)
            ->withApplication($application)
            ->withEnvironment($environment)
            ->withUser($user);

        $expected = <<<JSON
{
    "id": "abcdef",
    "value": "property_value",
    "schema": "ab",
    "created": "2015-08-15T12:00:00Z",
    "application": "cd",
    "environment": "ef",
    "user": 1234
}
JSON;

        $this->assertSame($expected, json_encode($property, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $property = new Property;

        $expected = <<<JSON
{
    "id": "",
    "value": "",
    "schema": null,
    "created": null,
    "application": null,
    "environment": null,
    "user": null
}
JSON;

        $this->assertSame($expected, json_encode($property, JSON_PRETTY_PRINT));
    }
}
