<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit_Framework_TestCase;

class JobStatusEnumTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('pending', JobStatusEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'pending',
            'running',
            'deploying',
            'success',
            'failure',
            'removed'
        ];

        $this->assertSame($expected, JobStatusEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, JobStatusEnum::isValid('derp'));
        $this->assertSame(false, JobStatusEnum::isValid('herp'));
        $this->assertSame(false, JobStatusEnum::isValid(1234));
        $this->assertSame(false, JobStatusEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, JobStatusEnum::isValid('pending'));
        $this->assertSame(true, JobStatusEnum::isValid('running'));
        $this->assertSame(true, JobStatusEnum::isValid('deploying'));
        $this->assertSame(true, JobStatusEnum::isValid('success'));
        $this->assertSame(true, JobStatusEnum::isValid('failure'));
        $this->assertSame(true, JobStatusEnum::isValid('removed'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('pending', JobStatusEnum::ensureValid('pending'));
        $this->assertSame('running', JobStatusEnum::ensureValid('running'));
        $this->assertSame('deploying', JobStatusEnum::ensureValid('deploying'));
        $this->assertSame('success', JobStatusEnum::ensureValid('success'));
        $this->assertSame('failure', JobStatusEnum::ensureValid('failure'));
        $this->assertSame('removed', JobStatusEnum::ensureValid('removed'));

        $this->assertSame('pending', JobStatusEnum::ensureValid('Pending'));
        $this->assertSame('deploying', JobStatusEnum::ensureValid('DEPLOYING'));
        $this->assertSame('removed', JobStatusEnum::ensureValid('Removed'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        JobStatusEnum::ensureValid($option);
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
