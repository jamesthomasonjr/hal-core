<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\User;

use Hal\Core\Entity\Organization;
use Hal\Core\Entity\User;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class UserTokenTest extends TestCase
{
    public function testDefaultValues()
    {
        $token = new UserToken;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $token->id());
        $this->assertInstanceOf(TimePoint::class, $token->created());

        $this->assertSame('', $token->name());
        $this->assertSame('', $token->value());

        $this->assertSame(null, $token->user());
        $this->assertSame(null, $token->organization());
    }

    public function testProperties()
    {
        $user = new User;
        $org = new Organization;

        $token = (new UserToken('abcdef'))
            ->withName('my token')
            ->withValue('tokenid')
            ->withUser($user)
            ->withOrganization($org);

        $this->assertSame('abcdef', $token->id());
        $this->assertSame('my token', $token->name());
        $this->assertSame('tokenid', $token->value());

        $this->assertSame($user, $token->user());
    }

    public function testSerialization()
    {
        $user = new User('9101');
        $org = new Organization('1112');

        $token = (new UserToken('1234', new TimePoint(2017, 12, 31, 12, 0, 0, 'UTC')))
            ->withName('my token')
            ->withValue('tokenid')
            ->withUser($user)
            ->withOrganization($org);

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2017-12-31T12:00:00Z",
    "name": "my token",
    "value": "tokenid",
    "user_id": "9101",
    "organization_id": "1112"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($token, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $token = new UserToken('1', new TimePoint(2017, 12, 31, 12, 0, 0, 'UTC'));

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2017-12-31T12:00:00Z",
    "name": "",
    "value": "",
    "user_id": null,
    "organization_id": null
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($token, JSON_PRETTY_PRINT));
    }
}
