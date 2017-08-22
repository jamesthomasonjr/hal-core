<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class TimeCreatedTraitTest extends TestCase
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
