<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class UserPermissionTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $perm = new UserPermission;

        $this->assertStringMatchesFormat('%x', $perm->id());
        $this->assertSame('member', $perm->type());

        $this->assertSame(null, $perm->user());
        $this->assertSame(null, $perm->application());
        $this->assertSame(null, $perm->organization());
    }

    public function testProperties()
    {
        $user = new User;
        $application = new Application;
        $organization = new Organization;
        $environment = new Environment;

        $perm = (new UserPermission('abcd', 'admin'))
            ->withUser($user)
            ->withApplication($application)
            ->withOrganization($organization)
            ->withEnvironment($environment);

        $this->assertSame('abcd', $perm->id());
        $this->assertSame('admin', $perm->type());

        $this->assertSame($user, $perm->user());
        $this->assertSame($application, $perm->application());
        $this->assertSame($organization, $perm->organization());
        $this->assertSame($environment, $perm->environment());
    }

    public function testSerialization()
    {
        $user = new User('1234');
        $application = new Application('5678');
        $organization = new Organization('9101');
        $environment = new Environment('1112');

        $perm = (new UserPermission('abcd', 'admin'))
            ->withUser($user)
            ->withApplication($application)
            ->withOrganization($organization)
            ->withEnvironment($environment);

        $expected = <<<JSON
{
    "id": "abcd",
    "type": "admin",
    "user_id": "1234",
    "application_id": "5678",
    "organization_id": "9101",
    "environment_id": "1112"
}
JSON;

        $this->assertSame($expected, json_encode($perm, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $perm = new UserPermission('1');

        $expected = <<<JSON
{
    "id": "1",
    "type": "member",
    "user_id": null,
    "application_id": null,
    "organization_id": null,
    "environment_id": null
}
JSON;

        $this->assertSame($expected, json_encode($perm, JSON_PRETTY_PRINT));
    }
}
