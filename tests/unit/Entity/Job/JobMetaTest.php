<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\Job;

use Hal\Core\Entity\Job;
use Hal\Core\Entity\JobType\Build;
use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class JobMetaTest extends TestCase
{
    public function testDefaultValues()
    {
        $meta = new JobMeta;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $meta->id());
        $this->assertInstanceOf(TimePoint::class, $meta->created());

        $this->assertSame('', $meta->name());
        $this->assertSame('', $meta->value());
    }

    public function testProperties()
    {
        $time = new TimePoint(2017, 8, 15, 12, 0, 0, 'UTC');

        $meta = (new JobMeta('1234', $time))
            ->withName('test-prop')
            ->withValue('derp')
            ->withJob(new Build('abcd'));

        $this->assertSame('1234', $meta->id());
        $this->assertSame($time, $meta->created());
        $this->assertSame('test-prop', $meta->name());
        $this->assertSame('derp', $meta->value());
        $this->assertSame('abcd', $meta->job()->id());
    }

    public function testSerialization()
    {
        $time = new TimePoint(2017, 8, 15, 12, 0, 0, 'UTC');

        $meta = (new JobMeta('1234', $time))
            ->withName('test-prop')
            ->withValue('derp')
            ->withJob(new Job('5678'));

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2017-08-15T12:00:00Z",
    "name": "test-prop",
    "value": "derp",
    "job_id": "5678"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($meta, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $time = new TimePoint(2017, 8, 15, 12, 0, 0, 'UTC');

        $meta = (new JobMeta('1', $time))
            ->withJob(new Job('1234'));

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2017-08-15T12:00:00Z",
    "name": "",
    "value": "",
    "job_id": "1234"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($meta, JSON_PRETTY_PRINT));
    }
}
