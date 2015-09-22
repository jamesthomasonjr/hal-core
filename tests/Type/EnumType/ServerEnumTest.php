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

class ServerEnumTest extends PHPUnit_Framework_TestCase
{
    private $platform;

    public function setUp()
    {
        if (!ServerEnum::hasType(ServerEnum::TYPE)) {
            ServerEnum::addType(ServerEnum::TYPE, ServerEnum::CLASS);
        }

        $this->platform = Mockery::mock(AbstractPlatform::CLASS);
    }

    public function testName()
    {
        $type = ServerEnum::getType(ServerEnum::TYPE);

        $this->assertSame(ServerEnum::TYPE, $type->getName());
    }

    public function testSQLDeclaration()
    {
        $type = ServerEnum::getType(ServerEnum::TYPE);
        $actual = $type->getSqlDeclaration([], $this->platform);

        $expected = "ENUM('rsync', 'eb', 'ec2', 's3', 'cd') COMMENT '(DC2Type:serverenum)'";

        $this->assertEquals($expected, $actual);
    }

    public function testConvertToDBValue()
    {
        $type = ServerEnum::getType(ServerEnum::TYPE);

        $actual = $type->convertToDatabaseValue('eb', $this->platform);
        $this->assertSame('eb', $actual);
    }

   /**
    * @expectedException InvalidArgumentException
    */
    public function testConvertToInvalidDBValueThrowsException()
    {
        $type = ServerEnum::getType(ServerEnum::TYPE);

        $actual = $type->convertToDatabaseValue('derp', $this->platform);
    }

    public function testConvertToPHPValue()
    {
        $type = ServerEnum::getType(ServerEnum::TYPE);

        $actual = $type->convertToPHPValue('derp', $this->platform);
        $this->assertSame('derp', $actual);
    }
}
