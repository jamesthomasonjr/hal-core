<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\System;

use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class SystemSettingTest extends TestCase
{
    public function testDefaultValues()
    {
        $setting = new SystemSetting;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $setting->id());
        $this->assertInstanceOf(TimePoint::class, $setting->created());

        $this->assertSame('', $setting->name());
        $this->assertSame('', $setting->value());
    }

    public function testProperties()
    {
        $setting = (new SystemSetting('1234'))
            ->withName('test-prop')
            ->withValue('derp');

        $this->assertSame('1234', $setting->id());
        $this->assertSame('test-prop', $setting->name());
        $this->assertSame('derp', $setting->value());
    }

    public function testSerialization()
    {
        $setting = (new SystemSetting('1234', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC')))
            ->withName('test-prop')
            ->withValue('derp');

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2018-01-01T12:00:00Z",
    "name": "test-prop",
    "value": "derp"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($setting, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $setting = new SystemSetting('1', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC'));

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2018-01-01T12:00:00Z",
    "name": "",
    "value": ""
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($setting, JSON_PRETTY_PRINT));
    }
}
