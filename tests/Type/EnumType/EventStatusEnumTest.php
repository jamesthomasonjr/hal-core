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

class EventStatusEnumTest extends PHPUnit_Framework_TestCase
{
    private $platform;

    public function setUp()
    {
        if (!EventStatusEnum::hasType(EventStatusEnum::TYPE)) {
            EventStatusEnum::addType(EventStatusEnum::TYPE, EventStatusEnum::CLASS);
        }

        $this->platform = Mockery::mock(AbstractPlatform::CLASS);
    }

    public function testName()
    {
        $type = EventStatusEnum::getType(EventStatusEnum::TYPE);

        $this->assertSame(EventStatusEnum::TYPE, $type->getName());
    }

    public function testSQLDeclaration()
    {
        $type = EventStatusEnum::getType(EventStatusEnum::TYPE);
        $actual = $type->getSqlDeclaration([], $this->platform);

        $expected = "ENUM('info', 'success', 'failure') COMMENT '(DC2Type:eventstatusenum)'";

        $this->assertEquals($expected, $actual);
    }

    public function testConvertToDBValue()
    {
        $type = EventStatusEnum::getType(EventStatusEnum::TYPE);

        $actual = $type->convertToDatabaseValue('success', $this->platform);
        $this->assertSame('success', $actual);
    }

   /**
    * @expectedException InvalidArgumentException
    */
    public function testConvertToInvalidDBValueThrowsException()
    {
        $type = EventStatusEnum::getType(EventStatusEnum::TYPE);

        $actual = $type->convertToDatabaseValue('derp', $this->platform);
    }

    public function testConvertToPHPValue()
    {
        $type = EventStatusEnum::getType(EventStatusEnum::TYPE);

        $actual = $type->convertToPHPValue('derp', $this->platform);
        $this->assertSame('derp', $actual);
    }
}
