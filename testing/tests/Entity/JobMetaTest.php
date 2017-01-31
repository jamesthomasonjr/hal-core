<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Type\EnumException;
use PHPUnit_Framework_TestCase;
use QL\MCP\Common\Time\TimePoint;

class JobMetaTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $meta = new JobMeta;

        $this->assertStringMatchesFormat('%x', $meta->id());
        $this->assertSame('', $meta->parentID());
        $this->assertSame('', $meta->name());
        $this->assertSame('', $meta->value());
    }

    public function testProperties()
    {
        $time = new TimePoint(2017, 8, 15, 12, 0, 0, 'UTC');

        $meta = (new JobMeta)
            ->withID('1234')
            ->withCreated($time)
            ->withName('test-prop')
            ->withValue('derp')
            ->withParentID('abcd');

        $this->assertSame('1234', $meta->id());
        $this->assertSame($time, $meta->created());
        $this->assertSame('test-prop', $meta->name());
        $this->assertSame('derp', $meta->value());
        $this->assertSame('abcd', $meta->parentID());
    }

    public function testSerialization()
    {
        $time = new TimePoint(2017, 8, 15, 12, 0, 0, 'UTC');

        $meta = (new JobMeta)
            ->withID('1234')
            ->withCreated($time)
            ->withName('test-prop')
            ->withValue('derp')
            ->withParentID('abcd');

        $expected = <<<JSON
{
    "id": "1234",
    "created": "2017-08-15T12:00:00Z",
    "name": "test-prop",
    "value": "derp",
    "parent_id": "abcd"
}
JSON;

        $this->assertSame($expected, json_encode($meta, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $time = new TimePoint(2017, 8, 15, 12, 0, 0, 'UTC');

        $meta = (new JobMeta('1'))
            ->withCreated($time);

        $expected = <<<JSON
{
    "id": "1",
    "created": "2017-08-15T12:00:00Z",
    "name": "",
    "value": "",
    "parent_id": ""
}
JSON;

        $this->assertSame($expected, json_encode($meta, JSON_PRETTY_PRINT));
    }
}
