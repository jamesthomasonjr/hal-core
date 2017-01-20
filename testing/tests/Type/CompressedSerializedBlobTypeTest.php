<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Mockery;
use PHPUnit_Framework_TestCase;

class CompressedSerializedBlobTypeTest extends PHPUnit_Framework_TestCase
{
    private $platform;

    public function setUp()
    {
        if (!CompressedSerializedBlobType::hasType('compressedserialized')) {
            CompressedSerializedBlobType::addType('compressedserialized', CompressedSerializedBlobType::CLASS);
        }

        $this->platform = Mockery::mock(AbstractPlatform::CLASS, [
            'getDateTimeFormatString' => 'Y-m-d H:i:s'
        ]);
    }

    public function testName()
    {
        $type = CompressedSerializedBlobType::getType('compressedserialized');

        $this->assertSame('compressedserialized', $type->getName());
    }

    public function testSQLDeclaration()
    {
        $this->platform
            ->shouldReceive('getBlobTypeDeclarationSQL')
            ->andReturn('mediumblob');

        $type = CompressedSerializedBlobType::getType('compressedserialized');
        $actual = $type->getSqlDeclaration([], $this->platform);

        $this->assertEquals('mediumblob', $actual);
    }

    public function testConvertingTimepointToDB()
    {
        $value = [
            'a' => 'test1',
            'b' => 'test2'
        ];

        $expected = base64_decode('eJxLtDKyqi62MrRSSlSyLrYytVIqSS0uMQSxgWJJSGJGSta1AC48DQo=');

        $type = CompressedSerializedBlobType::getType('compressedserialized');
        $actual = $type->convertToDatabaseValue($value, $this->platform);

        $this->assertSame($expected, $actual);
    }

    public function testGettingStringDatetimeFromDB()
    {
        $value = base64_decode('eJxLtDKyqi62MrRSSlSyLrYytVIqSS0uMQSxgWJJSGJGSta1AC48DQo=');

        $expected = [
            'a' => 'test1',
            'b' => 'test2'
        ];

        $type = CompressedSerializedBlobType::getType('compressedserialized');
        $actual = $type->convertToPHPValue($value, $this->platform);

        $this->assertEquals($expected, $actual);
    }}

