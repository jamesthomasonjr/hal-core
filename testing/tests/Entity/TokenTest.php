<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class TokenTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $token = new Token;

        $this->assertSame('', $token->id());
        $this->assertSame('', $token->label());
        $this->assertSame('', $token->value());

        $this->assertSame(null, $token->user());
    }

    public function testProperties()
    {
        $user = new User;

        $token = (new Token('abcdef'))
            ->withLabel('my token')
            ->withValue('tokenid')
            ->withUser($user);

        $this->assertSame('abcdef', $token->id());
        $this->assertSame('my token', $token->label());
        $this->assertSame('tokenid', $token->value());

        $this->assertSame($user, $token->user());
    }

    public function testSerialization()
    {
        $user = (new User)->withId(9101);

        $token = (new Token)
            ->withId(1234)
            ->withLabel('my token')
            ->withValue('tokenid')
            ->withUser($user);

        $expected = <<<JSON
{
    "id": 1234,
    "label": "my token",
    "value": "tokenid",
    "user": 9101
}
JSON;

        $this->assertSame($expected, json_encode($token, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $token = new Token;

        $expected = <<<JSON
{
    "id": "",
    "label": "",
    "value": "",
    "user": null
}
JSON;

        $this->assertSame($expected, json_encode($token, JSON_PRETTY_PRINT));
    }
}
