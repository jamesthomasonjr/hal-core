<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit\Framework\TestCase;

class UserSettingsTest extends TestCase
{
    public function testDefaultValues()
    {
        $settings = new UserSettings;

        $this->assertStringMatchesFormat('%x', $settings->id());
        $this->assertSame(null, $settings->user());

        $this->assertSame([], $settings->favoriteApplications());
    }

    public function testProperties()
    {
        $user = new User;
        $app1 = (new Application('app1'));
        $app2 = (new Application('app2'));
        $app3 = (new Application('app3'));

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
        $app1 = (new Application('app1'));
        $app2 = (new Application('app2'));
        $app3 = (new Application('app3'));

        $settings = (new UserSettings)
            ->withFavoriteApplication($app1)
            ->withFavoriteApplication($app2);

        $this->assertSame(true, $settings->isFavoriteApplication($app1));
        $this->assertSame(true, $settings->isFavoriteApplication($app2));
        $this->assertSame(false, $settings->isFavoriteApplication($app3));
    }

    public function testSerialization()
    {
        $user = new User('5678');
        $app1 = (new Application('app1'));
        $app2 = (new Application('app2'));

        $settings = (new UserSettings('1234'))
            ->withUser($user)
            ->withFavoriteApplication($app1)
            ->withFavoriteApplication($app2);

        $expected = <<<JSON
{
    "id": "1234",
    "favorite_applications": [
        "app1",
        "app2"
    ],
    "user_id": "5678"
}
JSON;

        $this->assertSame($expected, json_encode($settings, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $settings = new UserSettings('1');

        $expected = <<<JSON
{
    "id": "1",
    "favorite_applications": [],
    "user_id": null
}
JSON;

        $this->assertSame($expected, json_encode($settings, JSON_PRETTY_PRINT));
    }

}
