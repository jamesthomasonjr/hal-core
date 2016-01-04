<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class DeploymentPoolTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $pool = new DeploymentPool;

        $this->assertSame('', $pool->id());
        $this->assertSame('', $pool->name());
        $this->assertSame(null, $pool->view());

        $this->assertCount(0, $pool->deployments());
    }

    public function testProperties()
    {
        $view = new DeploymentView;

        $pool = (new DeploymentPool('abcd'))
            ->withName('pool name')
            ->withView($view);

        $pool->deployments()->add(new Deployment);
        $pool->deployments()->add(new Deployment);

        $this->assertSame('abcd', $pool->id());
        $this->assertSame('pool name', $pool->name());
        $this->assertSame($view, $pool->view());

        $this->assertCount(2, $pool->deployments());
    }

    public function testSerialization()
    {
        $view = new DeploymentView('efgh');

        $pool = (new DeploymentPool('abcd'))
            ->withName('pool name')
            ->withView($view);

        $expected = <<<JSON
{
    "id": "abcd",
    "name": "pool name",
    "view": "efgh"
}
JSON;

        $this->assertSame($expected, json_encode($pool, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $pool = new DeploymentPool;

        $expected = <<<JSON
{
    "id": "",
    "name": "",
    "view": null
}
JSON;

        $this->assertSame($expected, json_encode($pool, JSON_PRETTY_PRINT));
    }
}
