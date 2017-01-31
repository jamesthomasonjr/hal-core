<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class TargetPoolTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $pool = new TargetPool;

        $this->assertStringMatchesFormat('%x', $pool->id());
        $this->assertSame('', $pool->name());
        $this->assertSame(null, $pool->view());

        $this->assertCount(0, $pool->targets());
    }

    public function testProperties()
    {
        $view = new TargetView;

        $pool = (new TargetPool('abcd'))
            ->withName('pool name')
            ->withView($view);

        $pool->targets()->add(new Target);
        $pool->targets()->add(new Target);

        $this->assertSame('abcd', $pool->id());
        $this->assertSame('pool name', $pool->name());
        $this->assertSame($view, $pool->view());

        $this->assertCount(2, $pool->targets());
    }

    public function testSerialization()
    {
        $view = new TargetView('efgh');

        $pool = (new TargetPool('abcd'))
            ->withName('pool name')
            ->withView($view);

        $expected = <<<JSON
{
    "id": "abcd",
    "name": "pool name",
    "view_id": "efgh"
}
JSON;

        $this->assertSame($expected, json_encode($pool, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $pool = new TargetPool('1');

        $expected = <<<JSON
{
    "id": "1",
    "name": "",
    "view_id": null
}
JSON;

        $this->assertSame($expected, json_encode($pool, JSON_PRETTY_PRINT));
    }
}
