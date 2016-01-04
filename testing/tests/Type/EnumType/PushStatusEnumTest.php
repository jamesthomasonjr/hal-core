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

class PushStatusEnumTest extends PHPUnit_Framework_TestCase
{
    private $platform;

    public function setUp()
    {
        if (!PushStatusEnum::hasType(PushStatusEnum::TYPE)) {
            PushStatusEnum::addType(PushStatusEnum::TYPE, PushStatusEnum::CLASS);
        }

        $this->platform = Mockery::mock(AbstractPlatform::CLASS);
    }

    public function testName()
    {
        $type = PushStatusEnum::getType(PushStatusEnum::TYPE);

        $this->assertSame(PushStatusEnum::TYPE, $type->getName());
    }

    public function testSQLDeclaration()
    {
        $type = PushStatusEnum::getType(PushStatusEnum::TYPE);
        $actual = $type->getSqlDeclaration([], $this->platform);

        $expected = "ENUM('Waiting', 'Pushing', 'Error', 'Success') COMMENT '(DC2Type:pushstatusenum)'";

        $this->assertEquals($expected, $actual);
    }

    public function testConvertToDBValue()
    {
        $type = PushStatusEnum::getType(PushStatusEnum::TYPE);

        $actual = $type->convertToDatabaseValue('Pushing', $this->platform);
        $this->assertSame('Pushing', $actual);
    }

   /**
    * @expectedException InvalidArgumentException
    */
    public function testConvertToInvalidDBValueThrowsException()
    {
        $type = PushStatusEnum::getType(PushStatusEnum::TYPE);

        $actual = $type->convertToDatabaseValue('derp', $this->platform);
    }

    public function testConvertToPHPValue()
    {
        $type = PushStatusEnum::getType(PushStatusEnum::TYPE);

        $actual = $type->convertToPHPValue('derp', $this->platform);
        $this->assertSame('derp', $actual);
    }
}
