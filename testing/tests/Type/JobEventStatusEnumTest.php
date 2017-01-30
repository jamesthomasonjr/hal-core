<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit_Framework_TestCase;

class JobEventStatusEnumTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('info', JobEventStatusEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'info',
            'success',
            'failure'
        ];

        $this->assertSame($expected, JobEventStatusEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, JobEventStatusEnum::isValid('derp'));
        $this->assertSame(false, JobEventStatusEnum::isValid('herp'));
        $this->assertSame(false, JobEventStatusEnum::isValid(1234));
        $this->assertSame(false, JobEventStatusEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, JobEventStatusEnum::isValid('info'));
        $this->assertSame(true, JobEventStatusEnum::isValid('success'));
        $this->assertSame(true, JobEventStatusEnum::isValid('failure'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('info', JobEventStatusEnum::ensureValid('info'));
        $this->assertSame('success', JobEventStatusEnum::ensureValid('success'));
        $this->assertSame('failure', JobEventStatusEnum::ensureValid('failure'));

        $this->assertSame('info', JobEventStatusEnum::ensureValid('INFO'));
        $this->assertSame('success', JobEventStatusEnum::ensureValid('Success'));
        $this->assertSame('failure', JobEventStatusEnum::ensureValid('Failure'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        JobEventStatusEnum::ensureValid($option);
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
