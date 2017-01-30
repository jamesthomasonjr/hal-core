<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit_Framework_TestCase;

class AuditActionEnumTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('create', AuditActionEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'create',
            'update',
            'delete'
        ];

        $this->assertSame($expected, AuditActionEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, AuditActionEnum::isValid('derp'));
        $this->assertSame(false, AuditActionEnum::isValid('herp'));
        $this->assertSame(false, AuditActionEnum::isValid(1234));
        $this->assertSame(false, AuditActionEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, AuditActionEnum::isValid('create'));
        $this->assertSame(true, AuditActionEnum::isValid('update'));
        $this->assertSame(true, AuditActionEnum::isValid('delete'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('create', AuditActionEnum::ensureValid('create'));
        $this->assertSame('update', AuditActionEnum::ensureValid('update'));
        $this->assertSame('delete', AuditActionEnum::ensureValid('delete'));

        $this->assertSame('create', AuditActionEnum::ensureValid('Create'));
        $this->assertSame('update', AuditActionEnum::ensureValid('UPDATE'));
        $this->assertSame('delete', AuditActionEnum::ensureValid('DELete'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        AuditActionEnum::ensureValid($option);
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
