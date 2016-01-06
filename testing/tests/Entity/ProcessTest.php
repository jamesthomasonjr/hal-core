<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;
use QL\MCP\Common\Time\TimePoint;

class ProcessTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $process = new Process;

        $this->assertSame('', $process->id());
        $this->assertSame(null, $process->created());
        $this->assertSame(null, $process->user());

        $this->assertSame('Pending', $process->status());
        $this->assertSame('', $process->message());
        $this->assertSame([], $process->context());

        $this->assertSame('', $process->parent());
        $this->assertSame('', $process->parentType());
        $this->assertSame('', $process->child());
        $this->assertSame('', $process->childType());
    }

    public function testProperties()
    {
        $user = new User;
        $build = new Build('abcd1234');

        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $process = (new Process('1234'))
            ->withCreated($time)
            ->withUser($user)

            ->withStatus('Aborted')
            ->withMessage('test message')
            ->WithContext([
                'test1' => 'abcdef',
                'test2' => '123456'
            ])

            ->withParent($build)
            ->withChildType('child2');

        $this->assertSame('1234', $process->id());
        $this->assertSame($time, $process->created());
        $this->assertSame($user, $process->user());

        $this->assertSame('Aborted', $process->status());
        $this->assertSame('test message', $process->message());
        $this->assertSame([
                'test1' => 'abcdef',
                'test2' => '123456'
            ], $process->context());

        $this->assertSame('abcd1234', $process->parent());
        $this->assertSame('Build', $process->parentType());
        $this->assertSame('', $process->child());
        $this->assertSame('child2', $process->childType());
    }

    public function testSerialization()
    {
        $user = new User;
        $build = new Build('abcd1234');

        $time = new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC');

        $process = (new Process('1234'))
            ->withCreated($time)
            ->withUser($user)

            ->withStatus('Aborted')
            ->withMessage('test message')
            ->WithContext([
                'test1' => 'abcdef',
                'test2' => '123456'
            ])

            ->withParent($build)
            ->withChildType('child2');

        $expected = <<<JSON
{
    "id": "1234",
    "created": "2015-08-15T12:00:00Z",
    "user": null,
    "status": "Aborted",
    "message": "test message",
    "context": {
        "test1": "abcdef",
        "test2": "123456"
    },
    "parent": "abcd1234",
    "parentType": "Build",
    "child": "",
    "childType": "child2"
}
JSON;

        $this->assertSame($expected, json_encode($process, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $process = new Process;

        $expected = <<<JSON
{
    "id": "",
    "created": null,
    "user": null,
    "status": "Pending",
    "message": "",
    "context": [],
    "parent": "",
    "parentType": "",
    "child": "",
    "childType": ""
}
JSON;

        $this->assertSame($expected, json_encode($process, JSON_PRETTY_PRINT));
    }

}
