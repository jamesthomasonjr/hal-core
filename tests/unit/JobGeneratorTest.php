<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core;

use PHPUnit\Framework\TestCase;

class JobGeneratorTest extends TestCase
{
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

    public function testBuildIDIsGenerated()
    {
        $generator = new JobGenerator(JobGenerator::BASE58, 6);

        $actual = $generator->generateBuildID();

        $unique = substr($actual, 5);

        $this->assertSame(11, strlen($actual));
        $this->assertSame('b', $actual[0]);

        foreach (str_split($actual) as $letter) {
            $this->assertSame(true, strpos(JobGenerator::BASE58, $letter) !== false);
        }
    }

    public function testReleaseIDIsGenerated()
    {
        $generator = new JobGenerator(JobGenerator::BASE58, 5);

        $actual = $generator->generateReleaseID();

        $unique = substr($actual, 5);

        $this->assertSame(10, strlen($actual));
        $this->assertSame('r', $actual[0]);

        foreach (str_split($actual) as $letter) {
            $this->assertSame(true, strpos(JobGenerator::BASE58, $letter) !== false);
        }
    }
}
