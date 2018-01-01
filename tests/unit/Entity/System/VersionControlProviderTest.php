<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\System;

use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class VersionControlProviderTest extends TestCase
{
    public function testDefaultValues()
    {
        $provider = new VersionControlProvider;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $provider->id());
        $this->assertInstanceOf(TimePoint::class, $provider->created());

        $this->assertSame('', $provider->name());
        $this->assertSame('ghe', $provider->type());

        $this->assertSame([], $provider->parameters());
    }

    public function testProperties()
    {
        $provider = new VersionControlProvider('1234');

        $provider
            ->withType('gh')
            ->withName('My GitHub Org')
            ->withParameter('github.api_token', '1234')
            ->withParameter('github.organization', 'my-org');

        $this->assertSame('1234', $provider->id());
        $this->assertSame('My GitHub Org', $provider->name());
        $this->assertSame('gh', $provider->type());

        $this->assertSame('1234', $provider->parameter('github.api_token'));
        $this->assertSame('my-org', $provider->parameter('github.organization'));
    }

    public function testSerialization()
    {
        $provider = new VersionControlProvider('1234', new TimePoint(2018, 1, 3, 12, 0, 0, 'UTC'));

        $provider
            ->withType('git')
            ->withName('My git server')
            ->withParameter('git.server', 'git.example.com')
            ->withParameter('git.user', 'gituser')
            ->withParameter('git.key_path', '/path/to/ssh/key');

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2018-01-03T12:00:00Z",
    "name": "My git server",
    "type": "git",
    "parameters": {
        "git.server": "git.example.com",
        "git.user": "gituser",
        "git.key_path": "\/path\/to\/ssh\/key"
    }
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($provider, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $provider = new VersionControlProvider('1234', new TimePoint(2018, 1, 3, 12, 0, 0, 'UTC'));

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2018-01-03T12:00:00Z",
    "name": "",
    "type": "ghe",
    "parameters": []
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($provider, JSON_PRETTY_PRINT));
    }

    public function testInvalidTypeEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid VCSProviderEnum option.');

        $provider = new VersionControlProvider;
        $provider->withType('derp');
    }
}
