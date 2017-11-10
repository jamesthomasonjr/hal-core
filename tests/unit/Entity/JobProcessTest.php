<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class JobProcessTest extends TestCase
{
    public function testDefaultValues()
    {
        $process = new JobProcess;

        $this->assertStringMatchesFormat('%x', $process->id());
        $this->assertInstanceOf(TimePoint::class, $process->created());
        $this->assertSame(null, $process->user());

        $this->assertSame('pending', $process->status());
        $this->assertSame('', $process->message());
        $this->assertSame([], $process->parameters());

        $this->assertSame('', $process->parentID());
        $this->assertSame('', $process->childID());
        $this->assertSame('', $process->childType());
    }

    public function testProperties()
    {
        $user = new User;
        $build = new Build('abcd');
        $release = new Release('efgh');

        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $process = (new JobProcess('1234'))
            ->withCreated($time)
            ->withUser($user)

            ->withStatus('Aborted')
            ->withMessage('test message')
            ->withParameters([
                'test1' => 'abcdef',
                'test2' => '123456'
            ])

            ->withParent($build)
            ->withChild($release);

        $this->assertSame('1234', $process->id());
        $this->assertSame($time, $process->created());
        $this->assertSame($user, $process->user());

        $this->assertSame('aborted', $process->status());
        $this->assertSame('test message', $process->message());
        $this->assertSame([
                'test1' => 'abcdef',
                'test2' => '123456'
            ], $process->parameters());

        $this->assertSame('abcd', $process->parentID());
        $this->assertSame('efgh', $process->childID());
        $this->assertSame('Release', $process->childType());
    }

    public function testSerialization()
    {
        $user = new User('456');
        $build = new Build('abcd');
        $release = new Release('efgh');

        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $process = (new JobProcess('1234'))
            ->withCreated($time)
            ->withUser($user)

            ->withStatus('Aborted')
            ->withMessage('test message')
            ->withParameters([
                'test1' => 'abcdef',
                'test2' => '123456'
            ])

            ->withParent($build)
            ->withChild($release);

        $expected = <<<JSON
{
    "id": "1234",
    "created": "2015-08-15T12:00:00Z",
    "status": "aborted",
    "user_id": "456",
    "message": "test message",
    "parameters": {
        "test1": "abcdef",
        "test2": "123456"
    },
    "parent_id": "abcd",
    "child_id": "efgh",
    "child_type": "Release"
}
JSON;

        $this->assertSame($expected, json_encode($process, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');
        $process = new JobProcess('1', $time);

        $expected = <<<JSON
{
    "id": "1",
    "created": "2015-08-15T12:00:00Z",
    "status": "pending",
    "user_id": null,
    "message": "",
    "parameters": [],
    "parent_id": "",
    "child_id": "",
    "child_type": ""
}
JSON;

        $this->assertSame($expected, json_encode($process, JSON_PRETTY_PRINT));
    }

    public function testInvalidEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid process status option.');

        $process = new JobProcess('id');
        $process->withStatus('derp');
    }

    public function testChildTypeSetCorrectly()
    {
        $user = new User('456');
        $build = new Build('abcd');
        $release = new Release('efgh');

        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $processWithRelease = (new JobProcess('1234'))
            ->withCreated($time)
            ->withUser($user)

            ->withStatus('Aborted')
            ->withMessage('test message')
            ->withParameters([
                'test1' => 'abcdef',
                'test2' => '123456'
            ])

            ->withParent($build)
            ->withChild($release);

        $processWithBuild = (new JobProcess('1234'))
            ->withCreated($time)
            ->withUser($user)

            ->withStatus('Aborted')
            ->withMessage('test message')
            ->withParameters([
                'test1' => 'abcdef',
                'test2' => '123456'
            ])

            ->withChild($build);

        $this->assertSame('Release', $processWithRelease->childType());
        $this->assertSame('Build', $processWithBuild->childType());
    }
}
