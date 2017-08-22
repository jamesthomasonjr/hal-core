<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit\Framework\TestCase;

class OrganizationTest extends TestCase
{
    public function testDefaultValues()
    {
        $org = new Organization;

        $this->assertStringMatchesFormat('%x', $org->id());
        $this->assertSame('', $org->identifier());
        $this->assertSame('', $org->name());
    }

    public function testProperties()
    {
        $org = (new Organization('1234'))
            ->withIdentifier('group-id')
            ->withName('Org Name');

        $this->assertSame('1234', $org->id());
        $this->assertSame('group-id', $org->identifier());
        $this->assertSame('Org Name', $org->name());
    }

    public function testSerialization()
    {
        $org = (new Organization)
            ->withID('1234')
            ->withIdentifier('org-id')
            ->withName('My Organization Name');

        $expected = <<<JSON
{
    "id": "1234",
    "identifier": "org-id",
    "name": "My Organization Name"
}
JSON;

        $this->assertSame($expected, json_encode($org, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $org = new Organization('1');

        $expected = <<<JSON
{
    "id": "1",
    "identifier": "",
    "name": ""
}
JSON;

        $this->assertSame($expected, json_encode($org, JSON_PRETTY_PRINT));
    }
}
