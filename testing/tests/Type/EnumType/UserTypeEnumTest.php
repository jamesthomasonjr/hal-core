<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Type\EnumType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Mockery;
use PHPUnit_Framework_TestCase;

class UserTypeEnumTest extends PHPUnit_Framework_TestCase
{
    private $platform;

    public function setUp()
    {
        if (!UserTypeEnum::hasType(UserTypeEnum::TYPE)) {
            UserTypeEnum::addType(UserTypeEnum::TYPE, UserTypeEnum::CLASS);
        }

        $this->platform = Mockery::mock(AbstractPlatform::CLASS);
    }

    public function testName()
    {
        $type = UserTypeEnum::getType(UserTypeEnum::TYPE);

        $this->assertSame(UserTypeEnum::TYPE, $type->getName());
    }

    public function testSQLDeclaration()
    {
        $type = UserTypeEnum::getType(UserTypeEnum::TYPE);
        $actual = $type->getSqlDeclaration([], $this->platform);

        $expected = "ENUM('pleb', 'lead', 'btn_pusher', 'super') COMMENT '(DC2Type:usertypeenum)'";

        $this->assertEquals($expected, $actual);
    }

    public function testConvertToDBValue()
    {
        $type = UserTypeEnum::getType(UserTypeEnum::TYPE);

        $actual = $type->convertToDatabaseValue('super', $this->platform);
        $this->assertSame('super', $actual);
    }

   /**
    * @expectedException InvalidArgumentException
    */
    public function testConvertToInvalidDBValueThrowsException()
    {
        $type = UserTypeEnum::getType(UserTypeEnum::TYPE);

        $actual = $type->convertToDatabaseValue('derp', $this->platform);
    }

    public function testConvertToPHPValue()
    {
        $type = UserTypeEnum::getType(UserTypeEnum::TYPE);

        $actual = $type->convertToPHPValue('derp', $this->platform);
        $this->assertSame('derp', $actual);
    }
}
