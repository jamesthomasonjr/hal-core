<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core;

use PHPUnit_Framework_TestCase;

class RandomGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        mt_srand(1234);
    }

    public function tearDown()
    {
        mt_srand();
    }

    public function testIDIsGenerated()
    {
        $generator = new RandomGenerator;

        $this->assertSame('cf907f669f4242fca3a69cb3379ac577', $generator());
    }
}
