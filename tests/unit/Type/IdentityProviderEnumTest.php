<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit\Framework\TestCase;

class IdentityProviderEnumTest extends TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('internal', IdentityProviderEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'internal',
            'ldap',
            'gh',
            'ghe',
        ];

        $this->assertSame($expected, IdentityProviderEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, IdentityProviderEnum::isValid('derp'));
        $this->assertSame(false, IdentityProviderEnum::isValid('herp'));
        $this->assertSame(false, IdentityProviderEnum::isValid(1234));
        $this->assertSame(false, IdentityProviderEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, IdentityProviderEnum::isValid('internal'));
        $this->assertSame(true, IdentityProviderEnum::isValid('ldap'));
        $this->assertSame(true, IdentityProviderEnum::isValid('gh'));
        $this->assertSame(true, IdentityProviderEnum::isValid('ghe'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('internal', IdentityProviderEnum::ensureValid('internal'));
        $this->assertSame('ldap', IdentityProviderEnum::ensureValid('ldap'));
        $this->assertSame('gh', IdentityProviderEnum::ensureValid('gh'));
        $this->assertSame('ghe', IdentityProviderEnum::ensureValid('ghe'));

        $this->assertSame('internal', IdentityProviderEnum::ensureValid('Internal'));
        $this->assertSame('ldap', IdentityProviderEnum::ensureValid('Ldap'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        IdentityProviderEnum::ensureValid($option);
    }

    public function providerInvalidOptions()
    {
        return [
            ['derp'],
            ['herp'],
            [1234],
            [['derp', 'herp']]
        ];
    }
}
