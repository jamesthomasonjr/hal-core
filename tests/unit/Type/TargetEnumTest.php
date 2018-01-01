<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit\Framework\TestCase;

class targetEnumTest extends TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('rsync', targetEnum::defaultOption());
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

        $this->assertSame($expected, targetEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, targetEnum::isValid('derp'));
        $this->assertSame(false, targetEnum::isValid('herp'));
        $this->assertSame(false, targetEnum::isValid(1234));
        $this->assertSame(false, targetEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, targetEnum::isValid('rsync'));
        $this->assertSame(true, targetEnum::isValid('eb'));
        $this->assertSame(true, targetEnum::isValid('s3'));
        $this->assertSame(true, targetEnum::isValid('cd'));
        $this->assertSame(true, targetEnum::isValid('script'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('rsync', targetEnum::ensureValid('rsync'));
        $this->assertSame('eb', targetEnum::ensureValid('eb'));
        $this->assertSame('s3', targetEnum::ensureValid('s3'));
        $this->assertSame('cd', targetEnum::ensureValid('cd'));
        $this->assertSame('script', targetEnum::ensureValid('script'));

        $this->assertSame('rsync', targetEnum::ensureValid('RSYNC'));
        $this->assertSame('eb', targetEnum::ensureValid('EB'));
        $this->assertSame('s3', targetEnum::ensureValid('S3'));
        $this->assertSame('cd', targetEnum::ensureValid('Cd'));
        $this->assertSame('script', targetEnum::ensureValid('Script'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        targetEnum::ensureValid($option);
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
