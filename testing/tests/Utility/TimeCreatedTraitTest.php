<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use PHPUnit_Framework_TestCase;
use QL\MCP\Common\Time\TimePoint;

class TimeCreatedTraitTest extends PHPUnit_Framework_TestCase
{
    public function testIsGenerated()
    {
        $dummy = new TimeCreatedTraitDummy;

        $value = $dummy->generateCreatedTime();

        $this->assertInstanceOf(TimePoint::class, $value);
    }
}

class TimeCreatedTraitDummy
{
    use TimeCreatedTrait;
}
