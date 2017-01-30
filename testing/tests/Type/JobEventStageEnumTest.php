<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit_Framework_TestCase;

class JobEventStageEnumTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('unknown', JobEventStageEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'unknown',
            'build.created',
            'build.start',
            'build.running',
            'build.end',
            'build.success',
            'build.failure',

            'release.created',
            'release.start',
            'release.deploying',
            'release.end',
            'release.success',
            'release.failure',
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
        $this->assertSame(true, JobEventStageEnum::isValid('build.created'));
        $this->assertSame(true, JobEventStageEnum::isValid('build.start'));
        $this->assertSame(true, JobEventStageEnum::isValid('build.running'));
        $this->assertSame(true, JobEventStageEnum::isValid('build.end'));
        $this->assertSame(true, JobEventStageEnum::isValid('build.success'));
        $this->assertSame(true, JobEventStageEnum::isValid('build.failure'));

        $this->assertSame(true, JobEventStageEnum::isValid('release.created'));
        $this->assertSame(true, JobEventStageEnum::isValid('release.start'));
        $this->assertSame(true, JobEventStageEnum::isValid('release.deploying'));
        $this->assertSame(true, JobEventStageEnum::isValid('release.end'));
        $this->assertSame(true, JobEventStageEnum::isValid('release.success'));
        $this->assertSame(true, JobEventStageEnum::isValid('release.failure'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('unknown', JobEventStageEnum::ensureValid('unknown'));
        $this->assertSame('build.created', JobEventStageEnum::ensureValid('build.created'));
        $this->assertSame('build.start', JobEventStageEnum::ensureValid('build.start'));
        $this->assertSame('build.running', JobEventStageEnum::ensureValid('build.running'));
        $this->assertSame('build.end', JobEventStageEnum::ensureValid('build.end'));
        $this->assertSame('build.success', JobEventStageEnum::ensureValid('build.success'));
        $this->assertSame('build.failure', JobEventStageEnum::ensureValid('build.failure'));

        $this->assertSame('release.created', JobEventStageEnum::ensureValid('release.created'));
        $this->assertSame('release.start', JobEventStageEnum::ensureValid('release.start'));
        $this->assertSame('release.deploying', JobEventStageEnum::ensureValid('release.deploying'));
        $this->assertSame('release.end', JobEventStageEnum::ensureValid('release.end'));
        $this->assertSame('release.success', JobEventStageEnum::ensureValid('release.success'));
        $this->assertSame('release.failure', JobEventStageEnum::ensureValid('release.failure'));

        $this->assertSame('build.start', JobEventStageEnum::ensureValid('BUILD.Start'));
        $this->assertSame('build.running', JobEventStageEnum::ensureValid('Build.running'));
        $this->assertSame('release.success', JobEventStageEnum::ensureValid('Release.Success'));
        $this->assertSame('release.failure', JobEventStageEnum::ensureValid('release.FAILURE'));
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
