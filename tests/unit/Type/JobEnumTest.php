<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit\Framework\TestCase;

class JobEnumTest extends TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('build', JobEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'build',
            'release',
        ];

        $this->assertSame($expected, JobEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, JobEnum::isValid('derp'));
        $this->assertSame(false, JobEnum::isValid('herp'));
        $this->assertSame(false, JobEnum::isValid(1234));
        $this->assertSame(false, JobEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, JobEnum::isValid('build'));
        $this->assertSame(true, JobEnum::isValid('release'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('build', JobEnum::ensureValid('build'));
        $this->assertSame('release', JobEnum::ensureValid('release'));

        $this->assertSame('build', JobEnum::ensureValid('BUILD'));
        $this->assertSame('release', JobEnum::ensureValid('Release'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        JobEnum::ensureValid($option);
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
