<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit\Framework\TestCase;

class TargetEnumTest extends TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('rsync', TargetEnum::defaultOption());
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

        $this->assertSame($expected, TargetEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, TargetEnum::isValid('derp'));
        $this->assertSame(false, TargetEnum::isValid('herp'));
        $this->assertSame(false, TargetEnum::isValid(1234));
        $this->assertSame(false, TargetEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, TargetEnum::isValid('rsync'));
        $this->assertSame(true, TargetEnum::isValid('eb'));
        $this->assertSame(true, TargetEnum::isValid('s3'));
        $this->assertSame(true, TargetEnum::isValid('cd'));
        $this->assertSame(true, TargetEnum::isValid('script'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('rsync', TargetEnum::ensureValid('rsync'));
        $this->assertSame('eb', TargetEnum::ensureValid('eb'));
        $this->assertSame('s3', TargetEnum::ensureValid('s3'));
        $this->assertSame('cd', TargetEnum::ensureValid('cd'));
        $this->assertSame('script', TargetEnum::ensureValid('script'));

        $this->assertSame('rsync', TargetEnum::ensureValid('RSYNC'));
        $this->assertSame('eb', TargetEnum::ensureValid('EB'));
        $this->assertSame('s3', TargetEnum::ensureValid('S3'));
        $this->assertSame('cd', TargetEnum::ensureValid('Cd'));
        $this->assertSame('script', TargetEnum::ensureValid('Script'));
    }

    public function testFormattedValues()
    {
        $this->assertSame('Unknown', TargetEnum::format('xxx'));
        $this->assertSame('RSync', TargetEnum::format('rsync'));
        $this->assertSame('Elastic Beanstalk', TargetEnum::format('eb'));
        $this->assertSame('S3', TargetEnum::format('s3'));
        $this->assertSame('CodeDeploy', TargetEnum::format('cd'));
        $this->assertSame('Script', TargetEnum::format('script'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        TargetEnum::ensureValid($option);
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
