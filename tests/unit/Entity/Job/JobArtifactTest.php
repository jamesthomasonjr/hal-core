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

class JobArtifactTest extends TestCase
{
    public function testDefaultValues()
    {
        $artifact = new JobArtifact;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $artifact->id());
        $this->assertInstanceOf(TimePoint::class, $artifact->created());

        $this->assertSame('', $artifact->name());
        $this->assertSame(false, $artifact->isRemovable());
        $this->assertSame([], $artifact->parameters());

        // $this->assertSame(null, $event->job());
    }

    public function testProperties()
    {
        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $artifact = (new JobArtifact('abcd', $time1))
            ->withName('main')
            ->withIsRemovable(true)
            ->withJob(new Build('1234'))
            ->withParameters(['test' => 'value']);

        $this->assertSame('abcd', $artifact->id());
        $this->assertSame($time1, $artifact->created());

        $this->assertSame('main', $artifact->name());
        $this->assertSame(true, $artifact->isRemovable());

        $this->assertSame('1234', $artifact->job()->id());
        $this->assertSame(['test' => 'value'], $artifact->parameters());
    }

    public function testSerialization()
    {
        $time1 = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $artifact = (new JobArtifact('abcd', $time1))
            ->withName('main')
            ->withIsRemovable(true)
            ->withJob(new Build('1234'))
            ->withParameters(['test' => 'value']);

        $expected = <<<JSON_TEXT
{
    "id": "abcd",
    "created": "2015-08-15T12:00:00Z",
    "name": "main",
    "is_removable": true,
    "parameters": {
        "test": "value"
    },
    "job_id": "1234"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($artifact, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $artifact = new JobArtifact('1', $time);
        $artifact->withJob(new Job('1234'));


        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2015-08-15T12:00:00Z",
    "name": "",
    "is_removable": false,
    "parameters": [],
    "job_id": "1234"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($artifact, JSON_PRETTY_PRINT));
    }
}
