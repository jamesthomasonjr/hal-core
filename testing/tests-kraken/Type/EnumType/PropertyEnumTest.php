<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Kraken\Core\Type\EnumType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Mockery;
use PHPUnit_Framework_TestCase;

class PropertyEnumTest extends PHPUnit_Framework_TestCase
{
    private $platform;

    public function setUp()
    {
        if (!PropertyEnum::hasType(PropertyEnum::TYPE)) {
            PropertyEnum::addType(PropertyEnum::TYPE, PropertyEnum::CLASS);
        }

        $this->platform = Mockery::mock(AbstractPlatform::CLASS);
    }

    public function testName()
    {
        $type = PropertyEnum::getType(PropertyEnum::TYPE);

        $this->assertSame(PropertyEnum::TYPE, $type->getName());
    }

    public function testSQLDeclaration()
    {
        $type = PropertyEnum::getType(PropertyEnum::TYPE);
        $actual = $type->getSqlDeclaration([], $this->platform);

        $expected = "ENUM('string', 'strings', 'bool', 'int', 'float') COMMENT '(DC2Type:propertyenum)'";

        $this->assertEquals($expected, $actual);
    }

    public function testConvertToDBValue()
    {
        $type = PropertyEnum::getType(PropertyEnum::TYPE);

        $actual = $type->convertToDatabaseValue('strings', $this->platform);
        $this->assertSame('strings', $actual);
    }

   /**
    * @expectedException InvalidArgumentException
    */
    public function testConvertToInvalidDBValueThrowsException()
    {
        $type = PropertyEnum::getType(PropertyEnum::TYPE);

        $actual = $type->convertToDatabaseValue('derp', $this->platform);
    }

    public function testConvertToPHPValue()
    {
        $type = PropertyEnum::getType(PropertyEnum::TYPE);

        $actual = $type->convertToPHPValue('derp', $this->platform);
        $this->assertSame('derp', $actual);
    }
}
