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

class BuildStatusEnumTest extends PHPUnit_Framework_TestCase
{
    private $platform;

    public function setUp()
    {
        if (!BuildStatusEnum::hasType(BuildStatusEnum::TYPE)) {
            BuildStatusEnum::addType(BuildStatusEnum::TYPE, BuildStatusEnum::CLASS);
        }

        $this->platform = Mockery::mock(AbstractPlatform::CLASS);
    }

    public function testName()
    {
        $type = BuildStatusEnum::getType(BuildStatusEnum::TYPE);

        $this->assertSame(BuildStatusEnum::TYPE, $type->getName());
    }

    public function testSQLDeclaration()
    {
        $type = BuildStatusEnum::getType(BuildStatusEnum::TYPE);
        $actual = $type->getSqlDeclaration([], $this->platform);

        $expected = "ENUM('Waiting', 'Building', 'Success', 'Error', 'Removed') COMMENT '(DC2Type:buildstatusenum)'";

        $this->assertEquals($expected, $actual);
    }

    public function testConvertToDBValue()
    {
        $type = BuildStatusEnum::getType(BuildStatusEnum::TYPE);

        $actual = $type->convertToDatabaseValue('Building', $this->platform);
        $this->assertSame('Building', $actual);
    }

   /**
    * @expectedException InvalidArgumentException
    */
    public function testConvertToInvalidDBValueThrowsException()
    {
        $type = BuildStatusEnum::getType(BuildStatusEnum::TYPE);

        $actual = $type->convertToDatabaseValue('derp', $this->platform);
    }

    public function testConvertToPHPValue()
    {
        $type = BuildStatusEnum::getType(BuildStatusEnum::TYPE);

        $actual = $type->convertToPHPValue('derp', $this->platform);
        $this->assertSame('derp', $actual);
    }
}
