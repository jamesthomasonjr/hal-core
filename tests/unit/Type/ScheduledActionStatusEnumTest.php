<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit\Framework\TestCase;

class ScheduledActionStatusEnumTest extends TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('pending', ScheduledActionStatusEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'pending',
            'aborted',
            'launched'
        ];

        $this->assertSame($expected, ScheduledActionStatusEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, ScheduledActionStatusEnum::isValid('derp'));
        $this->assertSame(false, ScheduledActionStatusEnum::isValid('herp'));
        $this->assertSame(false, ScheduledActionStatusEnum::isValid(1234));
        $this->assertSame(false, ScheduledActionStatusEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, ScheduledActionStatusEnum::isValid('pending'));
        $this->assertSame(true, ScheduledActionStatusEnum::isValid('aborted'));
        $this->assertSame(true, ScheduledActionStatusEnum::isValid('launched'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('pending', ScheduledActionStatusEnum::ensureValid('pending'));
        $this->assertSame('aborted', ScheduledActionStatusEnum::ensureValid('aborted'));
        $this->assertSame('launched', ScheduledActionStatusEnum::ensureValid('launched'));

        $this->assertSame('pending', ScheduledActionStatusEnum::ensureValid('Pending'));
        $this->assertSame('aborted', ScheduledActionStatusEnum::ensureValid('ABORTED'));
        $this->assertSame('launched', ScheduledActionStatusEnum::ensureValid('LAUNCHed'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        ScheduledActionStatusEnum::ensureValid($option);
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
