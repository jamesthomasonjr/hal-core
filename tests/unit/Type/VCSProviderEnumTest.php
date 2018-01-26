<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit\Framework\TestCase;

class VCSProviderEnumTest extends TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('ghe', VCSProviderEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'git',
            'gh',
            'ghe'
        ];

        $this->assertSame($expected, VCSProviderEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, VCSProviderEnum::isValid('derp'));
        $this->assertSame(false, VCSProviderEnum::isValid('herp'));
        $this->assertSame(false, VCSProviderEnum::isValid(1234));
        $this->assertSame(false, VCSProviderEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, VCSProviderEnum::isValid('git'));
        $this->assertSame(true, VCSProviderEnum::isValid('gh'));
        $this->assertSame(true, VCSProviderEnum::isValid('ghe'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('git', VCSProviderEnum::ensureValid('git'));
        $this->assertSame('gh', VCSProviderEnum::ensureValid('gh'));
        $this->assertSame('ghe', VCSProviderEnum::ensureValid('ghe'));

        $this->assertSame('ghe', VCSProviderEnum::ensureValid('Ghe'));
        $this->assertSame('git', VCSProviderEnum::ensureValid('GIT'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        VCSProviderEnum::ensureValid($option);
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
