<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Entity;

use PHPUnit_Framework_TestCase;
use QL\MCP\Common\Time\TimePoint;

class SnapshotTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $snapshot = new Snapshot;

        $this->assertSame('', $snapshot->id());
        $this->assertSame('', $snapshot->key());
        $this->assertSame('', $snapshot->value());
        $this->assertSame('', $snapshot->dataType());
        $this->assertSame('', $snapshot->checksum());

        $this->assertSame(true, $snapshot->isSecure());

        $this->assertSame(null, $snapshot->created());
        $this->assertSame(null, $snapshot->configuration());
        $this->assertSame(null, $snapshot->property());
        $this->assertSame(null, $snapshot->schema());
    }

    public function testProperties()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $configuration = new Configuration;
        $property = new Property;
        $schema = new Schema;

        $snapshot = (new Snapshot('abcd'))
            ->withId('abcdef')
            ->withKey('property.key')
            ->withValue('property value')
            ->withDataType('strings')
            ->withChecksum('abcdef12345')
            ->withIsSecure(false)

            ->withCreated($time)
            ->withConfiguration($configuration)
            ->withProperty($property)
            ->withSchema($schema);

        $this->assertSame('abcdef', $snapshot->id());
        $this->assertSame('property.key', $snapshot->key());
        $this->assertSame('property value', $snapshot->value());
        $this->assertSame('strings', $snapshot->dataType());
        $this->assertSame('abcdef12345', $snapshot->checksum());

        $this->assertSame(false, $snapshot->isSecure());

        $this->assertSame($time, $snapshot->created());
        $this->assertSame($configuration, $snapshot->configuration());
        $this->assertSame($property, $snapshot->property());
        $this->assertSame($schema, $snapshot->schema());
    }

    public function testSerialization()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $configuration = new Configuration('ab');
        $property = new Property('cd');
        $schema = new Schema('ef');

        $snapshot = (new Snapshot('abcd'))
            ->withId('abcdef')
            ->withKey('property.key')
            ->withValue('property value')
            ->withDataType('strings')
            ->withChecksum('abcdef12345')
            ->withIsSecure(false)

            ->withCreated($time)
            ->withConfiguration($configuration)
            ->withProperty($property)
            ->withSchema($schema);


        $expected = <<<JSON
{
    "id": "abcdef",
    "key": "property.key",
    "value": "property value",
    "dataType": "strings",
    "checksum": "abcdef12345",
    "isSecure": false,
    "created": "2015-08-15T12:00:00+00:00",
    "configuration": "ab",
    "property": "cd",
    "schema": "ef"
}
JSON;

        $this->assertSame($expected, json_encode($snapshot, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $snapshot = new Snapshot;

        $expected = <<<JSON
{
    "id": "",
    "key": "",
    "value": "",
    "dataType": "",
    "checksum": "",
    "isSecure": true,
    "created": null,
    "configuration": null,
    "property": null,
    "schema": null
}
JSON;

        $this->assertSame($expected, json_encode($snapshot, JSON_PRETTY_PRINT));
    }
}
