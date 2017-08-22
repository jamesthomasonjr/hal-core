<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    public function testDefaultValues()
    {
        $group = new Group;

        $this->assertStringMatchesFormat('%x', $group->id());
        $this->assertSame('rsync', $group->type());
        $this->assertSame('', $group->name());

        $this->assertSame(null, $group->environment());
    }

    public function testProperties()
    {
        $environment = new Environment;

        $group = (new Group)
            ->withID('1234')
            ->withType('rsync')
            ->withName('hostname')
            ->withEnvironment($environment);

        $this->assertSame('1234', $group->id());
        $this->assertSame('rsync', $group->type());
        $this->assertSame('hostname', $group->name());
    }

    public function testIsAWS()
    {
        $group = new Group;

        $this->assertSame(true, $group->withType('cd')->isAWS());
        $this->assertSame(true, $group->withType('eb')->isAWS());
        $this->assertSame(true, $group->withType('s3')->isAWS());

        $this->assertSame(false, $group->withType('rsync')->isAWS());
        $this->assertSame(false, $group->withType('script')->isAWS());
    }

    public function testSerialization()
    {
        $group = (new Group('1234'))
            ->withType('rsync')
            ->withName('hostname')
            ->withEnvironment(new Environment('9101'));

        $expected = <<<JSON
{
    "id": "1234",
    "type": "rsync",
    "name": "hostname",
    "environment_id": "9101"
}
JSON;

        $this->assertSame($expected, json_encode($group, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $group = new Group('2');

        $expected = <<<JSON
{
    "id": "2",
    "type": "rsync",
    "name": "",
    "environment_id": null
}
JSON;

        $this->assertSame($expected, json_encode($group, JSON_PRETTY_PRINT));
    }

    public function testInvalidEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid group option.');

        $group = new Group('id');
        $group->withType('derp');
    }
}
