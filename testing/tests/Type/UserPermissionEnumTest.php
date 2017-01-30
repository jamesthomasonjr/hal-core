<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit_Framework_TestCase;

class UserPermissionEnumTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('member', UserPermissionEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'member',
            'owner',
            'admin',
            'super'
        ];

        $this->assertSame($expected, UserPermissionEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, UserPermissionEnum::isValid('derp'));
        $this->assertSame(false, UserPermissionEnum::isValid('herp'));
        $this->assertSame(false, UserPermissionEnum::isValid(1234));
        $this->assertSame(false, UserPermissionEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, UserPermissionEnum::isValid('member'));
        $this->assertSame(true, UserPermissionEnum::isValid('owner'));
        $this->assertSame(true, UserPermissionEnum::isValid('admin'));
        $this->assertSame(true, UserPermissionEnum::isValid('super'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('member', UserPermissionEnum::ensureValid('member'));
        $this->assertSame('owner', UserPermissionEnum::ensureValid('owner'));
        $this->assertSame('admin', UserPermissionEnum::ensureValid('admin'));
        $this->assertSame('super', UserPermissionEnum::ensureValid('super'));

        $this->assertSame('member', UserPermissionEnum::ensureValid('Member'));
        $this->assertSame('admin', UserPermissionEnum::ensureValid('Admin'));
        $this->assertSame('super', UserPermissionEnum::ensureValid('SUPER'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        UserPermissionEnum::ensureValid($option);
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
