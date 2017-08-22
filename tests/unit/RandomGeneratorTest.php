<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core;

use PHPUnit\Framework\TestCase;

class RandomGeneratorTest extends TestCase
{
    public function testIDIsGenerated()
    {
        $generator = new RandomGenerator;

        $this->assertStringMatchesFormat('%x', $generator());
    }
}
