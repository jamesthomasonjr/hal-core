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

class UserIdentityProviderTest extends TestCase
{
    public function testDefaultValues()
    {
        $provider = new UserIdentityProvider;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $provider->id());
        $this->assertInstanceOf(TimePoint::class, $provider->created());

        $this->assertSame('', $provider->name());
        $this->assertSame('internal', $provider->type());

        $this->assertSame([], $provider->parameters());
    }

    public function testProperties()
    {
        $provider = new UserIdentityProvider('1234');

        $provider
            ->withType('ghe')
            ->withName('My Auth System')
            ->withParameter('ghe.app_id', '1234');

        $this->assertSame('1234', $provider->id());
        $this->assertSame('My Auth System', $provider->name());
        $this->assertSame('ghe', $provider->type());

        $this->assertSame('1234', $provider->parameter('ghe.app_id'));
    }

    public function testSerialization()
    {
        $provider = new UserIdentityProvider('1234', new TimePoint(2018, 1, 3, 12, 0, 0, 'UTC'));

        $provider
            ->withType('ldap')
            ->withName('Corporate LDAP')
            ->withParameter('ldap.server', 'ldap.example.com')
            ->withParameter('ldap.domain', 'CORP')
            ->withParameter('ldap.dn', 'dc=mycompany,dc=corp');

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2018-01-03T12:00:00Z",
    "name": "Corporate LDAP",
    "type": "ldap",
    "parameters": {
        "ldap.server": "ldap.example.com",
        "ldap.domain": "CORP",
        "ldap.dn": "dc=mycompany,dc=corp"
    }
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($provider, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $provider = new UserIdentityProvider('1234', new TimePoint(2018, 1, 3, 12, 0, 0, 'UTC'));

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2018-01-03T12:00:00Z",
    "name": "",
    "type": "internal",
    "parameters": []
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($provider, JSON_PRETTY_PRINT));
    }

    public function testInvalidTypeEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid IdentityProviderEnum option.');

        $provider = new UserIdentityProvider;
        $provider->withType('derp');
    }
}
