<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use PHPUnit_Framework_TestCase;

class CredentialEnumTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultOption()
    {
        $this->assertSame('aws', CredentialEnum::defaultOption());
    }

    public function testTypes()
    {
        $expected = [
            'aws',
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

        $this->assertSame(true, CredentialEnum::isValid('aws'));
        $this->assertSame(true, CredentialEnum::isValid('privatekey'));
    }

    public function testEnsureValidWhenValidReturnsNormalizedValue()
    {
        $this->assertSame('aws', CredentialEnum::ensureValid('aws'));
        $this->assertSame('privatekey', CredentialEnum::ensureValid('privatekey'));

        $this->assertSame('aws', CredentialEnum::ensureValid('AWS'));
        $this->assertSame('privatekey', CredentialEnum::ensureValid('PrivateKey'));
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
