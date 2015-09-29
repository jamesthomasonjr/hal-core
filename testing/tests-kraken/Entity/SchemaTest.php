<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Kraken\Core\Entity;

use MCP\DataType\Time\TimePoint;
use PHPUnit_Framework_TestCase;
use QL\Hal\Core\Entity\User;

class SchemaTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $schema = new Schema;

        $this->assertSame('', $schema->id());
        $this->assertSame('', $schema->key());
        $this->assertSame('', $schema->dataType());
        $this->assertSame('', $schema->description());

        $this->assertSame(true, $schema->isSecure());

        $this->assertSame(null, $schema->created());
        $this->assertSame(null, $schema->application());
        $this->assertSame(null, $schema->user());
    }

    public function testProperties()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $user = new User;
        $application = new Application;

        $schema = (new Schema('abcd'))
            ->withId('abcdef')
            ->withKey('property.key')
            ->withDataType('strings')
            ->withDescription('description of property')
            ->withIsSecure(false)

            ->withCreated($time)
            ->withApplication($application)
            ->withUser($user);

        $this->assertSame('abcdef', $schema->id());
        $this->assertSame('property.key', $schema->key());
        $this->assertSame('strings', $schema->dataType());
        $this->assertSame('description of property', $schema->description());

        $this->assertSame(false, $schema->isSecure());

        $this->assertSame($time, $schema->created());
        $this->assertSame($application, $schema->application());
        $this->assertSame($user, $schema->user());
    }

    public function testSerialization()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $user = new User(1234);
        $application = new Application;

        $schema = (new Schema('abcd'))
            ->withId('abcdef')
            ->withKey('property.key')
            ->withDataType('strings')
            ->withDescription('description of property')
            ->withIsSecure(false)

            ->withCreated($time)
            ->withApplication($application)
            ->withUser($user);

        $expected = <<<JSON
{
    "id": "abcdef",
    "key": "property.key",
    "dataType": "strings",
    "description": "description of property",
    "isSecure": false,
    "created": "2015-08-15T12:00:00Z",
    "application": "",
    "user": 1234
}
JSON;

        $this->assertSame($expected, json_encode($schema, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $schema = new Schema;

        $expected = <<<JSON
{
    "id": "",
    "key": "",
    "dataType": "",
    "description": "",
    "isSecure": true,
    "created": null,
    "application": null,
    "user": null
}
JSON;

        $this->assertSame($expected, json_encode($schema, JSON_PRETTY_PRINT));
    }
}
