<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $user = new User;

        $this->assertStringMatchesFormat('%x', $user->id());
        $this->assertSame('', $user->username());
        $this->assertSame('', $user->name());
        $this->assertSame('', $user->email());

        $this->assertSame(false, $user->isDisabled());

        $this->assertInstanceOf(UserSettings::class, $user->settings());
        $this->assertSame($user, $user->settings()->user());

        $this->assertCount(0, $user->tokens());
    }

    public function testProperties()
    {
        $user = (new User('1234'))
            ->withUsername('BSmith1')
            ->withName('Smith, Bob')
            ->withEmail('name@quickenloans.com')
            ->withIsDisabled(true);

        $user->tokens()->add(new UserToken);
        $user->tokens()->add(new UserToken);

        $this->assertSame('1234', $user->id());
        $this->assertSame('BSmith1', $user->username());
        $this->assertSame('Smith, Bob', $user->name());
        $this->assertSame('name@quickenloans.com', $user->email());
        $this->assertSame(true, $user->isDisabled());

        $this->assertCount(2, $user->tokens());
    }

    public function testSerialization()
    {
        $user = (new User)
            ->withID('1234')
            ->withUsername('BSmith1')
            ->withName('Smith, Bob')
            ->withEmail('name@quickenloans.com')
            ->withIsDisabled(true);

        $expected = <<<JSON
{
    "id": "1234",
    "username": "BSmith1",
    "name": "Smith, Bob",
    "email": "name@quickenloans.com",
    "is_disabled": true
}
JSON;

        $this->assertSame($expected, json_encode($user, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $user = new User('1');

        $expected = <<<JSON
{
    "id": "1",
    "username": "",
    "name": "",
    "email": "",
    "is_disabled": false
}
JSON;

        $this->assertSame($expected, json_encode($user, JSON_PRETTY_PRINT));
    }
}
