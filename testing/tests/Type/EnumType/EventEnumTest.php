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

class EventEnumTest extends PHPUnit_Framework_TestCase
{
    private $platform;

    public function setUp()
    {
        if (!EventEnum::hasType(EventEnum::TYPE)) {
            EventEnum::addType(EventEnum::TYPE, EventEnum::CLASS);
        }

        $this->platform = Mockery::mock(AbstractPlatform::CLASS);
    }

    public function testName()
    {
        $type = EventEnum::getType(EventEnum::TYPE);

        $this->assertSame(EventEnum::TYPE, $type->getName());
    }

    public function testSQLDeclaration()
    {
        $type = EventEnum::getType(EventEnum::TYPE);
        $actual = $type->getSqlDeclaration([], $this->platform);

        $expected = "ENUM('build.created', 'build.start', 'build.building', 'build.end', 'build.success', 'build.failure', 'push.created', 'push.start', 'push.pushing', 'push.end', 'push.success', 'push.failure', 'unknown') COMMENT '(DC2Type:eventenum)'";

        $this->assertEquals($expected, $actual);
    }

    public function testConvertToDBValue()
    {
        $type = EventEnum::getType(EventEnum::TYPE);

        $actual = $type->convertToDatabaseValue('build.start', $this->platform);
        $this->assertSame('build.start', $actual);
    }

   /**
    * @expectedException InvalidArgumentException
    */
    public function testConvertToInvalidDBValueThrowsException()
    {
        $type = EventEnum::getType(EventEnum::TYPE);

        $actual = $type->convertToDatabaseValue('derp', $this->platform);
    }

    public function testConvertToPHPValue()
    {
        $type = EventEnum::getType(EventEnum::TYPE);

        $actual = $type->convertToPHPValue('derp', $this->platform);
        $this->assertSame('derp', $actual);
    }
}
