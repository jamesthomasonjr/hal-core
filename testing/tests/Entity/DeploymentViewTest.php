<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class DeploymentViewTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $view = new DeploymentView;

        $this->assertSame('', $view->id());
        $this->assertSame('', $view->name());
        $this->assertSame(null, $view->application());
        $this->assertSame(null, $view->environment());
        $this->assertSame(null, $view->user());

        $this->assertCount(0, $view->pools());
    }

    public function testProperties()
    {
        $app = new Application;
        $env = new Environment;
        $user = new User;

        $view = (new DeploymentView('abcd'))
            ->withName('view name')
            ->withApplication($app)
            ->withEnvironment($env)
            ->withUser($user);

        $view->pools()->add(new DeploymentPool);
        $view->pools()->add(new DeploymentPool);

        $this->assertSame('abcd', $view->id());
        $this->assertSame('view name', $view->name());
        $this->assertSame($app, $view->application());
        $this->assertSame($env, $view->environment());
        $this->assertSame($user, $view->user());

        $this->assertCount(2, $view->pools());
    }

    public function testSerialization()
    {
        $app = (new Application)->withId(1234);
        $env = (new Environment)->withId(5678);
        $user = (new User)->withId(9101);

        $view = (new DeploymentView('abcd'))
            ->withName('view name')
            ->withApplication($app)
            ->withEnvironment($env)
            ->withUser($user);

        $expected = <<<JSON
{
    "id": "abcd",
    "name": "view name",
    "application": 1234,
    "environment": 5678,
    "user": 9101
}
JSON;

        $this->assertSame($expected, json_encode($view, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $view = new DeploymentView;

        $expected = <<<JSON
{
    "id": "",
    "name": "",
    "application": null,
    "environment": null,
    "user": null
}
JSON;

        $this->assertSame($expected, json_encode($view, JSON_PRETTY_PRINT));
    }
}
