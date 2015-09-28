<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $user = new User;

        $this->assertSame(null, $user->id());
        $this->assertSame('', $user->handle());
        $this->assertSame('', $user->name());
        $this->assertSame('', $user->email());

        $this->assertSame(false, $user->isActive());
        $this->assertSame('', $user->githubToken());

        $this->assertCount(0, $user->tokens());
    }

    public function testProperties()
    {
        $user = (new User)
            ->withId(1234)
            ->withHandle('BSmith1')
            ->withName('Smith, Bob')
            ->withEmail('name@quickenloans.com')
            ->withIsActive(true);

        $user->tokens()->add(new Token);
        $user->tokens()->add(new Token);

        $this->assertSame(1234, $user->id());
        $this->assertSame('BSmith1', $user->handle());
        $this->assertSame('Smith, Bob', $user->name());
        $this->assertSame('name@quickenloans.com', $user->email());
        $this->assertSame(true, $user->isActive());

        $this->assertCount(2, $user->tokens());
    }

    public function testSerialization()
    {
        $user = (new User)
            ->withId(1234)
            ->withHandle('BSmith1')
            ->withName('Smith, Bob')
            ->withEmail('name@quickenloans.com')
            ->withIsActive(true);

        $expected = <<<JSON
{
    "id": 1234,
    "handle": "BSmith1",
    "name": "Smith, Bob",
    "email": "name@quickenloans.com",
    "isActive": true
}
JSON;

        $this->assertSame($expected, json_encode($user, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $user = new User;

        $expected = <<<JSON
{
    "id": null,
    "handle": "",
    "name": "",
    "email": "",
    "isActive": false
}
JSON;

        $this->assertSame($expected, json_encode($user, JSON_PRETTY_PRINT));
    }
}
