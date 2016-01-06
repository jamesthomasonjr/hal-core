<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class UserSettingsTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $settings = new UserSettings;

        $this->assertSame('', $settings->id());
        $this->assertSame(null, $settings->user());

        $this->assertSame([], $settings->favoriteApplications());
    }

    public function testProperties()
    {
        $user = new User;
        $app1 = (new Application)->withId('app1');
        $app2 = (new Application)->withId('app2');
        $app3 = (new Application)->withId('app3');

        $settings = (new UserSettings('1234'))
            ->withUser($user);

        $settings
            ->withFavoriteApplication($app1)
            ->withFavoriteApplication($app2)
            ->withFavoriteApplication($app3);

        $this->assertSame(['app1', 'app2', 'app3'], $settings->favoriteApplications());

        $settings->withoutFavoriteApplication($app2);

        $this->assertSame(['app1', 'app3'], $settings->favoriteApplications());

        $this->assertSame('1234', $settings->id());
        $this->assertSame($user, $settings->user());
    }

    public function testIsFavorite()
    {
        $app1 = (new Application)->withId('app1');
        $app2 = (new Application)->withId('app2');
        $app3 = (new Application)->withId('app3');

        $settings = (new UserSettings)
            ->withFavoriteApplication($app1)
            ->withFavoriteApplication($app2);

        $this->assertSame(true, $settings->isFavoriteApplication($app1));
        $this->assertSame(true, $settings->isFavoriteApplication($app2));
        $this->assertSame(false, $settings->isFavoriteApplication($app3));
    }

    public function testSerialization()
    {
        $user = new User;
        $app1 = (new Application)->withId('app1');
        $app2 = (new Application)->withId('app2');

        $settings = (new UserSettings('1234'))
            ->withUser($user)
            ->withFavoriteApplication($app1)
            ->withFavoriteApplication($app2);

        $expected = <<<JSON
{
    "id": "1234",
    "favoriteApplications": [
        "app1",
        "app2"
    ],
    "user": null
}
JSON;

        $this->assertSame($expected, json_encode($settings, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $settings = new UserSettings;

        $expected = <<<JSON
{
    "id": "",
    "favoriteApplications": [],
    "user": null
}
JSON;

        $this->assertSame($expected, json_encode($settings, JSON_PRETTY_PRINT));
    }

}
