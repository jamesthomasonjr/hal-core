<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Mockery;
use PHPUnit_Framework_TestCase;

class CompressedJSONArrayTypeTest extends PHPUnit_Framework_TestCase
{
    private $platform;

    public function setUp()
    {
        if (!CompressedJSONArrayType::hasType('compressed_json_array')) {
            CompressedJSONArrayType::addType('compressed_json_array', CompressedJSONArrayType::class);
        }

        $this->platform = Mockery::mock(AbstractPlatform::class);
    }

    public function testName()
    {
        $type = CompressedJSONArrayType::getType('compressed_json_array');

        $this->assertSame('compressed_json_array', $type->getName());
    }

    public function testSQLDeclaration()
    {
        $this->platform
            ->shouldReceive('getBlobTypeDeclarationSQL')
            ->andReturn('mediumblob');

        $type = CompressedJSONArrayType::getType('compressed_json_array');
        $actual = $type->getSqlDeclaration([], $this->platform);

        $this->assertEquals('mediumblob', $actual);
    }

    public function testConvertingArrayToDB()
    {
        $value = [
            'a' => 'test1',
            'b' => 'test2'
        ];

        $expected = gzcompress(json_encode($value));

        $type = CompressedJSONArrayType::getType('compressed_json_array');
        $actual = $type->convertToDatabaseValue($value, $this->platform);

        $this->assertSame($expected, $actual);
    }

    public function testGettingArrayFromDB()
    {
        $expected = [
            'a' => 'test1',
            'b' => 'test2'
        ];

        $value = gzcompress(json_encode($expected));

        $type = CompressedJSONArrayType::getType('compressed_json_array');
        $actual = $type->convertToPHPValue($value, $this->platform);

        $this->assertEquals($expected, $actual);
    }}

