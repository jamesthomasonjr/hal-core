<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use PHPUnit\Framework\TestCase;
use QL\MCP\Common\GUID;
use QL\MCP\Common\Time\TimePoint;

class EntityTraitTest extends TestCase
{
    public function testIsGenerated()
    {
        $dummy = new EntityTraitDummy;

        $this->assertNotInstanceOf(GUID::class, $dummy->id());
        $this->assertSame(36, strlen($dummy->id()));

        $this->assertInstanceOf(TimePoint::class, $dummy->created());
    }

    public function testIDCanBeProvided()
    {
        $dummy = new EntityTraitDummy('abc');

        $this->assertSame('abc', $dummy->id());
    }

    public function testTimeCanBeProvided()
    {
        $time = new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC');

        $dummy = new EntityTraitDummy('', $time);

        $this->assertSame($time, $dummy->created());
    }
}

class EntityTraitDummy
{
    use EntityTrait;

    /**
     * @param string $id
     * @param TimePoint|null $created
     */
    public function __construct($id = '', TimePoint $created = null)
    {
        $this->initializeEntity($id, $created);
    }
}
