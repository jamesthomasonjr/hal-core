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

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $value);
        $this->assertSame(36, strlen($value));
    }
}

class EntityIDTraitDummy
{
    use EntityIDTrait;
}
