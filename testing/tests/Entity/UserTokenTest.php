<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class UserTokenTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $token = new UserToken;

        $this->assertStringMatchesFormat('%x', $token->id());
        $this->assertSame('', $token->name());
        $this->assertSame('', $token->value());

        $this->assertSame(null, $token->user());
    }

    public function testProperties()
    {
        $user = new User;

        $token = (new UserToken('abcdef'))
            ->withName('my token')
            ->withValue('tokenid')
            ->withUser($user);

        $this->assertSame('abcdef', $token->id());
        $this->assertSame('my token', $token->name());
        $this->assertSame('tokenid', $token->value());

        $this->assertSame($user, $token->user());
    }

    public function testSerialization()
    {
        $user = new User('9101');
        $org = new Organization('1112');

        $token = (new UserToken)
            ->withID('1234')
            ->withName('my token')
            ->withValue('tokenid')
            ->withUser($user)
            ->withOrganization($org);

        $expected = <<<JSON
{
    "id": "1234",
    "name": "my token",
    "value": "tokenid",
    "user_id": "9101",
    "organization_id": "1112"
}
JSON;

        $this->assertSame($expected, json_encode($token, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $token = new UserToken('1');

        $expected = <<<JSON
{
    "id": "1",
    "name": "",
    "value": "",
    "user_id": null,
    "organization_id": null
}
JSON;

        $this->assertSame($expected, json_encode($token, JSON_PRETTY_PRINT));
    }
}
