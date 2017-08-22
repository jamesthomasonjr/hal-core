<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit\Framework\TestCase;

class GroupEnumTest extends TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('rsync', GroupEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'rsync',
            'eb',
            's3',
            'cd',
            'script'
        ];

        $this->assertSame($expected, GroupEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, GroupEnum::isValid('derp'));
        $this->assertSame(false, GroupEnum::isValid('herp'));
        $this->assertSame(false, GroupEnum::isValid(1234));
        $this->assertSame(false, GroupEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, GroupEnum::isValid('rsync'));
        $this->assertSame(true, GroupEnum::isValid('eb'));
        $this->assertSame(true, GroupEnum::isValid('s3'));
        $this->assertSame(true, GroupEnum::isValid('cd'));
        $this->assertSame(true, GroupEnum::isValid('script'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('rsync', GroupEnum::ensureValid('rsync'));
        $this->assertSame('eb', GroupEnum::ensureValid('eb'));
        $this->assertSame('s3', GroupEnum::ensureValid('s3'));
        $this->assertSame('cd', GroupEnum::ensureValid('cd'));
        $this->assertSame('script', GroupEnum::ensureValid('script'));

        $this->assertSame('rsync', GroupEnum::ensureValid('RSYNC'));
        $this->assertSame('eb', GroupEnum::ensureValid('EB'));
        $this->assertSame('s3', GroupEnum::ensureValid('S3'));
        $this->assertSame('cd', GroupEnum::ensureValid('Cd'));
        $this->assertSame('script', GroupEnum::ensureValid('Script'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        GroupEnum::ensureValid($option);
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
