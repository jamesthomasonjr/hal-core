<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core;

use PHPUnit_Framework_TestCase;

class JobGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        mt_srand(1234);
    }

    public function tearDown()
    {
        mt_srand();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidAlphabetThrowsException()
    {
        $generator = new JobGenerator('a');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidSizeThrowsException()
    {
        $generator = new JobGenerator(JobGenerator::BASE58, 2);
    }

    public function testIDIsGenerated()
    {
        $generator = new JobGenerator(JobGenerator::BASE58);

        $actual = $generator->generateBuildID();

        $unique = substr($actual, 6);

        $this->assertSame('b', $actual[0]);
        $this->assertSame('Pdv3f', $unique);
    }

    public function testLongIDIsGenerated()
    {
        $generator = new JobGenerator(JobGenerator::BASE58, 6);

        $actual = $generator->generateBuildID();

        $unique = substr($actual, 6);

        $this->assertSame('b', $actual[0]);
        $this->assertSame('3QQsHh', $unique);
    }

    public function testReleaseIDIsGenerated()
    {
        $generator = new JobGenerator(JobGenerator::BASE58, 5);

        $actual = $generator->generateReleaseID();

        $unique = substr($actual, 6);

        $this->assertSame('r', $actual[0]);
        $this->assertSame('Pdv3f', $unique);
    }
}
