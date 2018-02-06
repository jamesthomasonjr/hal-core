<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit\Framework\TestCase;

class JobEventStageEnumTest extends TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('unknown', JobEventStageEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'unknown',
            'created',
            'starting',
            'running',
            'ending',
            'finished',
            'success',
            'failure'
        ];

        $this->assertSame($expected, JobEventStageEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, JobEventStageEnum::isValid('derp'));
        $this->assertSame(false, JobEventStageEnum::isValid('herp'));
        $this->assertSame(false, JobEventStageEnum::isValid(1234));
        $this->assertSame(false, JobEventStageEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, JobEventStageEnum::isValid('unknown'));

        $this->assertSame(true, JobEventStageEnum::isValid('created'));
        $this->assertSame(true, JobEventStageEnum::isValid('starting'));
        $this->assertSame(true, JobEventStageEnum::isValid('running'));
        $this->assertSame(true, JobEventStageEnum::isValid('ending'));

        $this->assertSame(true, JobEventStageEnum::isValid('finished'));
        $this->assertSame(true, JobEventStageEnum::isValid('success'));
        $this->assertSame(true, JobEventStageEnum::isValid('failure'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('unknown', JobEventStageEnum::ensureValid('unknown'));

        $this->assertSame('created', JobEventStageEnum::ensureValid('created'));
        $this->assertSame('starting', JobEventStageEnum::ensureValid('starting'));
        $this->assertSame('running', JobEventStageEnum::ensureValid('running'));
        $this->assertSame('ending', JobEventStageEnum::ensureValid('ending'));

        $this->assertSame('finished', JobEventStageEnum::ensureValid('finished'));
        $this->assertSame('success', JobEventStageEnum::ensureValid('success'));
        $this->assertSame('failure', JobEventStageEnum::ensureValid('failure'));

        $this->assertSame('starting', JobEventStageEnum::ensureValid('Starting'));
        $this->assertSame('running', JobEventStageEnum::ensureValid('RUNNING'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        JobEventStageEnum::ensureValid($option);
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
