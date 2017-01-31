<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core;

use PHPUnit_Framework_TestCase;

class RandomGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function testIDIsGenerated()
    {
        $generator = new RandomGenerator;

        $this->assertStringMatchesFormat('%x', $generator());
    }
}
