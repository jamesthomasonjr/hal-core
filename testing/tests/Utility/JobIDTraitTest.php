<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use PHPUnit_Framework_TestCase;
use QL\MCP\Common\GUID;

class JobIDTraitTest extends PHPUnit_Framework_TestCase
{
    public function testBuildIDIsGenerated()
    {
        $dummy = new JobIDTraitDummy;

        $value = $dummy->generateBuildID();

        $this->assertNotInstanceOf(GUID::class, $value);
        $this->assertStringStartsWith('b', $value);
        $this->assertSame(10, strlen($value));
    }

    public function testReleaseIDIsGenerated()
    {
        $dummy = new JobIDTraitDummy;

        $value = $dummy->generateReleaseID();

        $this->assertNotInstanceOf(GUID::class, $value);
        $this->assertStringStartsWith('r', $value);
        $this->assertSame(10, strlen($value));
    }
}

class JobIDTraitDummy
{
    use JobIDTrait;
}
