<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit_Framework_TestCase;

class JobProcessStatusEnumTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('pending', JobProcessStatusEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'pending',
            'aborted',
            'launched'
        ];

        $this->assertSame($expected, JobProcessStatusEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, JobProcessStatusEnum::isValid('derp'));
        $this->assertSame(false, JobProcessStatusEnum::isValid('herp'));
        $this->assertSame(false, JobProcessStatusEnum::isValid(1234));
        $this->assertSame(false, JobProcessStatusEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, JobProcessStatusEnum::isValid('pending'));
        $this->assertSame(true, JobProcessStatusEnum::isValid('aborted'));
        $this->assertSame(true, JobProcessStatusEnum::isValid('launched'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('pending', JobProcessStatusEnum::ensureValid('pending'));
        $this->assertSame('aborted', JobProcessStatusEnum::ensureValid('aborted'));
        $this->assertSame('launched', JobProcessStatusEnum::ensureValid('launched'));

        $this->assertSame('pending', JobProcessStatusEnum::ensureValid('Pending'));
        $this->assertSame('aborted', JobProcessStatusEnum::ensureValid('ABORTED'));
        $this->assertSame('launched', JobProcessStatusEnum::ensureValid('LAUNCHed'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        JobProcessStatusEnum::ensureValid($option);
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
