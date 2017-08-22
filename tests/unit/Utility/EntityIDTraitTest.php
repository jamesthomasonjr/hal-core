<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use PHPUnit\Framework\TestCase;
use QL\MCP\Common\GUID;

class EntityIDTraitTest extends TestCase
{
    public function testIsGenerated()
    {
        $dummy = new EntityIDTraitDummy;

        $value = $dummy->generateEntityID();

        $this->assertNotInstanceOf(GUID::class, $value);
        $this->assertStringMatchesFormat('%x', $value);
        $this->assertSame(32, strlen($value));
    }
}

class EntityIDTraitDummy
{
    use EntityIDTrait;
}
