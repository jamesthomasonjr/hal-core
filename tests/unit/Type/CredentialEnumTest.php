<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit\Framework\TestCase;

class CredentialEnumTest extends TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('aws_static', CredentialEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'aws_role',
            'aws_static',
            'privatekey'
        ];

        $this->assertSame($expected, CredentialEnum::options());
    }

    public function testIsValidValues()
    {
        $this->assertSame(false, CredentialEnum::isValid('derp'));
        $this->assertSame(false, CredentialEnum::isValid('herp'));
        $this->assertSame(false, CredentialEnum::isValid(1234));
        $this->assertSame(false, CredentialEnum::isValid(['derp', 'herp']));

        $this->assertSame(true, CredentialEnum::isValid('aws_static'));
        $this->assertSame(true, CredentialEnum::isValid('privatekey'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('aws_static', CredentialEnum::ensureValid('aws_static'));
        $this->assertSame('privatekey', CredentialEnum::ensureValid('privatekey'));

        $this->assertSame('aws_static', CredentialEnum::ensureValid('AWS_STATic'));
        $this->assertSame('aws_role', CredentialEnum::ensureValid('AWS_ROLE'));
        $this->assertSame('privatekey', CredentialEnum::ensureValid('PrivateKey'));
    }

    public function testFormattedValues()
    {
        $this->assertSame('Unknown', CredentialEnum::format('xxx'));
        $this->assertSame('AWS STS Role', CredentialEnum::format('aws_role'));
        $this->assertSame('AWS Static Token', CredentialEnum::format('aws_static'));
        $this->assertSame('Private Key', CredentialEnum::format('privatekey'));
    }

    /**
     * @dataProvider providerInvalidOptions
     */
    public function testEnsureValidWhenInvalidThrowsException($option)
    {
        $this->expectException(EnumException::class);
        CredentialEnum::ensureValid($option);
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
