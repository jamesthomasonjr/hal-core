<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core;

use PHPUnit_Framework_TestCase;

class JobIdGeneratorTest extends PHPUnit_Framework_TestCase
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
        $generator = new JobIdGenerator('1', 'a');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidSizeThrowsException()
    {
        $generator = new JobIdGenerator('1', JobIdGenerator::BASE58, 2);
    }

    public function testIDIsGenerated()
    {
        $generator = new JobIdGenerator('1', JobIdGenerator::BASE58);

        $actual = $generator->generateBuildId();

        $version = substr($actual, 0, 3);
        $unique = substr($actual, 6);

        $this->assertSame('b1.', $version);
        $this->assertSame('Pdv3', $unique);
    }

    public function testLongIDIsGenerated()
    {
        $generator = new JobIdGenerator('1', JobIdGenerator::BASE58, 6);

        $actual = $generator->generateBuildId();

        $version = substr($actual, 0, 3);
        $unique = substr($actual, 6);

        $this->assertSame('b1.', $version);
        $this->assertSame('3QQsHh', $unique);
    }

    public function testPushIDIsGenerated()
    {
        $generator = new JobIdGenerator('1', JobIdGenerator::BASE58, 5);

        $actual = $generator->generatePushId();

        $version = substr($actual, 0, 3);
        $unique = substr($actual, 6);

        $this->assertSame('p1.', $version);
        $this->assertSame('Pdv3f', $unique);
    }
}
