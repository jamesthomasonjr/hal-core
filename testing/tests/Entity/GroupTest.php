<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class GroupTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $group = new Group;

        $this->assertSame(null, $group->id());
        $this->assertSame('', $group->key());
        $this->assertSame('', $group->name());
    }

    public function testProperties()
    {
        $group = (new Group)
            ->withId(1234)
            ->withKey('group-id')
            ->withName('Group Name');

        $this->assertSame(1234, $group->id());
        $this->assertSame('group-id', $group->key());
        $this->assertSame('Group Name', $group->name());
    }

    public function testSerialization()
    {
        $group = (new Group)
            ->withId(1234)
            ->withKey('group-id')
            ->withName('Group Name');

        $expected = <<<JSON
{
    "id": 1234,
    "identifier": "group-id",
    "name": "Group Name"
}
JSON;

        $this->assertSame($expected, json_encode($group, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $group = new Group;

        $expected = <<<JSON
{
    "id": null,
    "identifier": "",
    "name": ""
}
JSON;

        $this->assertSame($expected, json_encode($group, JSON_PRETTY_PRINT));
    }
}
