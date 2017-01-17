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

        $expected = "ENUM('rsync', 'eb', 's3', 'cd', 'script') COMMENT '(DC2Type:serverenum)'";

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
