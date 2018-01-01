<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\User;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Organization;
use Hal\Core\Entity\User;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class UserPermissionTest extends TestCase
{
    public function testDefaultValues()
    {
        $perm = new UserPermission;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $perm->id());
        $this->assertInstanceOf(TimePoint::class, $perm->created());

        $this->assertSame('member', $perm->type());

        // $this->assertSame(null, $perm->user());
        $this->assertSame(null, $perm->application());
        $this->assertSame(null, $perm->environment());
        $this->assertSame(null, $perm->organization());
    }

    public function testProperties()
    {
        $user = new User;
        $application = new Application;
        $organization = new Organization;
        $environment = new Environment;

        $perm = (new UserPermission('admin', 'abcd'))
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

        $perm = (new UserPermission('admin', '1234', new TimePoint(2017, 12, 31, 12, 0, 0, 'UTC')))
            ->withUser($user)
            ->withApplication($application)
            ->withOrganization($organization)
            ->withEnvironment($environment);

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2017-12-31T12:00:00Z",
    "type": "admin",
    "user_id": "1234",
    "application_id": "5678",
    "organization_id": "9101",
    "environment_id": "1112"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($perm, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $perm = new UserPermission('', '1', new TimePoint(2017, 12, 31, 12, 0, 0, 'UTC'));
        $perm->withUser(new User('1234'));

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2017-12-31T12:00:00Z",
    "type": "member",
    "user_id": "1234",
    "application_id": null,
    "organization_id": null,
    "environment_id": null
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($perm, JSON_PRETTY_PRINT));
    }
}
