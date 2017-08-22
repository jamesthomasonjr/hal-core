<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit\Framework\TestCase;

class SystemSettingTest extends TestCase
{
    public function testDefaultValues()
    {
        $setting = new SystemSetting;

        $this->assertStringMatchesFormat('%x', $setting->id());
        $this->assertSame('', $setting->name());
        $this->assertSame('', $setting->value());
    }

    public function testProperties()
    {
        $setting = (new SystemSetting)
            ->withID('1234')
            ->withName('test-prop')
            ->withValue('derp');

        $this->assertSame('1234', $setting->id());
        $this->assertSame('test-prop', $setting->name());
        $this->assertSame('derp', $setting->value());
    }

    public function testSerialization()
    {
        $setting = (new SystemSetting('1234'))
            ->withName('test-prop')
            ->withValue('derp');

        $expected = <<<JSON
{
    "id": "1234",
    "name": "test-prop",
    "value": "derp"
}
JSON;

        $this->assertSame($expected, json_encode($setting, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $setting = new SystemSetting('1');

        $expected = <<<JSON
{
    "id": "1",
    "name": "",
    "value": ""
}
JSON;

        $this->assertSame($expected, json_encode($setting, JSON_PRETTY_PRINT));
    }
}
