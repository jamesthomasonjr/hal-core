<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class UserPermissionTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $perm = new UserPermission;

        $this->assertSame('', $perm->id());

        $this->assertSame(false, $perm->isProduction());
        $this->assertSame(null, $perm->user());
        $this->assertSame(null, $perm->application());
    }

    public function testProperties()
    {
        $user = new User;
        $application = new Application;

        $perm = (new UserPermission('abcd'))
            ->withUser($user)
            ->withApplication($application)
            ->withIsProduction(true);

        $this->assertSame('abcd', $perm->id());
        $this->assertSame($user, $perm->user());
        $this->assertSame($application, $perm->application());
        $this->assertSame(true, $perm->isProduction());
    }

    public function testSerialization()
    {
        $user = (new User)->withId(1234);
        $application = (new Application)->withId(5678);

        $perm = (new UserPermission('abcd'))
            ->withUser($user)
            ->withApplication($application)
            ->withIsProduction(true);

        $expected = <<<JSON
{
    "id": "abcd",
    "isProduction": true,
    "user": 1234,
    "application": 5678
}
JSON;

        $this->assertSame($expected, json_encode($perm, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $perm = new UserPermission;

        $expected = <<<JSON
{
    "id": "",
    "isProduction": false,
    "user": null,
    "application": null
}
JSON;

        $this->assertSame($expected, json_encode($perm, JSON_PRETTY_PRINT));
    }
}
