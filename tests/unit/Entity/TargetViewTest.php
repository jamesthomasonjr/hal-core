<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit\Framework\TestCase;

class TargetViewTest extends TestCase
{
    public function testDefaultValues()
    {
        $view = new TargetView;

        $this->assertStringMatchesFormat('%x', $view->id());
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

        $view = (new TargetView('abcd'))
            ->withName('view name')
            ->withApplication($app)
            ->withEnvironment($env)
            ->withUser($user);

        $view->pools()->add(new TargetPool);
        $view->pools()->add(new TargetPool);

        $this->assertSame('abcd', $view->id());
        $this->assertSame('view name', $view->name());
        $this->assertSame($app, $view->application());
        $this->assertSame($env, $view->environment());
        $this->assertSame($user, $view->user());

        $this->assertCount(2, $view->pools());
    }

    public function testSerialization()
    {
        $app = new Application('1234');
        $env = new Environment('5678');
        $user = new User('9101');

        $view = (new TargetView('abcd'))
            ->withName('view name')
            ->withApplication($app)
            ->withEnvironment($env)
            ->withUser($user);

        $expected = <<<JSON
{
    "id": "abcd",
    "name": "view name",
    "application_id": "1234",
    "environment_id": "5678",
    "user_id": "9101"
}
JSON;

        $this->assertSame($expected, json_encode($view, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $view = new TargetView('1');

        $expected = <<<JSON
{
    "id": "1",
    "name": "",
    "application_id": null,
    "environment_id": null,
    "user_id": null
}
JSON;

        $this->assertSame($expected, json_encode($view, JSON_PRETTY_PRINT));
    }
}
