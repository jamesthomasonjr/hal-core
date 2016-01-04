<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class UserTypeTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $type = new UserType;

        $this->assertSame('', $type->id());
        $this->assertSame('', $type->type());

        $this->assertSame(null, $type->user());
        $this->assertSame(null, $type->application());
    }

    public function testProperties()
    {
        $user = new User;
        $application = new Application;

        $type = (new UserType('abcd'))
            ->withType('admin')
            ->withUser($user)
            ->withApplication($application);

        $this->assertSame('abcd', $type->id());
        $this->assertSame('admin', $type->type());

        $this->assertSame($user, $type->user());
        $this->assertSame($application, $type->application());
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
