<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
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
