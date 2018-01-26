<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class OrganizationTest extends TestCase
{
    public function testDefaultValues()
    {
        $org = new Organization;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $org->id());
        $this->assertInstanceOf(TimePoint::class, $org->created());

        $this->assertSame('', $org->name());
    }

    public function testProperties()
    {
        $org = (new Organization('1234'))
            ->withName('Org Name');

        $this->assertSame('1234', $org->id());
        $this->assertSame('Org Name', $org->name());
    }

    public function testSerialization()
    {
        $org = (new Organization('1234', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC')))
            ->withName('My Organization Name');

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2018-01-01T12:00:00Z",
    "name": "My Organization Name"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($org, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $org = new Organization('1', new TimePoint(2018, 1, 2, 12, 0, 0, 'UTC'));

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2018-01-02T12:00:00Z",
    "name": ""
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($org, JSON_PRETTY_PRINT));
    }
}
