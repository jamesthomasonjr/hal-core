<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Mockery;
use PHPUnit_Framework_TestCase;
use QL\MCP\Common\Time\TimePoint;
use stdClass;

class TimePointTypeTest extends PHPUnit_Framework_TestCase
{
    private $platform;

    public function setUp()
    {
        if (!TimePointType::hasType('timepoint')) {
            TimePointType::addType('timepoint', TimePointType::CLASS);
        }

        $this->platform = Mockery::mock(AbstractPlatform::CLASS, [
            'getDateTimeFormatString' => 'Y-m-d H:i:s'
        ]);
    }

    public function testName()
    {
        $type = TimePointType::getType('timepoint');

        $this->assertSame('timepoint', $type->getName());
    }

    public function testSQLDeclaration()
    {
        $this->platform
            ->shouldReceive('getTimeTypeDeclarationSQL')
            ->andReturn('datetime');

        $type = TimePointType::getType('timepoint');
        $actual = $type->getSqlDeclaration([], $this->platform);

        $this->assertEquals('datetime', $actual);
    }

    public function testConvertingTimepointToDB()
    {
        $value = new TimePoint(2015, 8, 10, 2, 30, 0, 'America/Detroit');

        $type = TimePointType::getType('timepoint');
        $actual = $type->convertToDatabaseValue($value, $this->platform);

        $this->assertSame('2015-08-10 06:30:00', $actual);
    }

    public function testConvertingInvalidValueToDBReturnsNull()
    {
        $value = new stdClass;

        $type = TimePointType::getType('timepoint');
        $actual = $type->convertToDatabaseValue($value, $this->platform);

        $this->assertSame(null, $actual);
    }

    public function testGettingInvalidFromDBReturnsNull()
    {
        $value = new stdClass;

        $type = TimePointType::getType('timepoint');
        $actual = $type->convertToPHPValue($value, $this->platform);

        $this->assertSame(null, $actual);
    }

    public function testGettingStringDatetimeFromDB()
    {
        $value = '2015-08-10 06:30:00';
        $expected = new TimePoint(2015, 8, 10, 2, 30, 0, 'America/Detroit');

        $type = TimePointType::getType('timepoint');
        $actual = $type->convertToPHPValue($value, $this->platform);

        $this->assertEquals($expected, $actual);
    }
}
