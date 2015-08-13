<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Type\EnumType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Mockery;
use PHPUnit_Framework_TestCase;

class CredentialEnumTest extends PHPUnit_Framework_TestCase
{
    private $platform;

    public function setUp()
    {
        if (!CredentialEnum::hasType(CredentialEnum::TYPE)) {
            CredentialEnum::addType(CredentialEnum::TYPE, CredentialEnum::CLASS);
        }

        $this->platform = Mockery::mock(AbstractPlatform::CLASS);
    }

    public function testName()
    {
        $type = CredentialEnum::getType(CredentialEnum::TYPE);

        $this->assertSame(CredentialEnum::TYPE, $type->getName());
    }

    public function testSQLDeclaration()
    {
        $type = CredentialEnum::getType(CredentialEnum::TYPE);
        $actual = $type->getSqlDeclaration([], $this->platform);

        $expected = "ENUM('aws', 'privatekey') COMMENT '(DC2Type:credentialenum)'";

        $this->assertEquals($expected, $actual);
    }

    public function testConvertToDBValue()
    {
        $type = CredentialEnum::getType(CredentialEnum::TYPE);

        $actual = $type->convertToDatabaseValue('privatekey', $this->platform);
        $this->assertSame('privatekey', $actual);
    }

   /**
    * @expectedException InvalidArgumentException
    */
    public function testConvertToInvalidDBValueThrowsException()
    {
        $type = CredentialEnum::getType(CredentialEnum::TYPE);

        $actual = $type->convertToDatabaseValue('derp', $this->platform);
    }

    public function testConvertToPHPValue()
    {
        $type = CredentialEnum::getType(CredentialEnum::TYPE);

        $actual = $type->convertToPHPValue('derp', $this->platform);
        $this->assertSame('derp', $actual);
    }
}
