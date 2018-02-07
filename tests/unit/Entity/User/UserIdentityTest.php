<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\User;

use Hal\Core\Entity\System\UserIdentityProvider;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class UserIdentityTest extends TestCase
{
    public function testDefaultValues()
    {
        $identity = new UserIdentity;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $identity->id());
        $this->assertInstanceOf(TimePoint::class, $identity->created());

        $this->assertSame([], $identity->parameters());
        // $this->assertSame(null, $identity->provider());
    }

    public function testProperties()
    {
        $provider = new UserIdentityProvider;

        $identity = (new UserIdentity('1234'))
            ->withParameter('this', 'that')
            ->withProvider($provider);

        $this->assertSame('1234', $identity->id());
        $this->assertSame('that', $identity->parameter('this'));
    }

    public function testSerialization()
    {
        $provider = new UserIdentityProvider('5678');

        $identity = (new UserIdentity('1234', new TimePoint(2017, 12, 31, 12, 0, 0, 'UTC')))
            ->withParameter('this', 'that')
            ->withProviderUniqueID('6868')
            ->withProvider($provider);

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2017-12-31T12:00:00Z",
    "parameters": {
        "this": "that"
    },
    "provider_unique_id": "6868",
    "provider_id": "5678"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($identity, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $provider = new UserIdentityProvider('5678');

        $identity = new UserIdentity('1', new TimePoint(2017, 12, 31, 12, 0, 0, 'UTC'));
        $identity->withProvider($provider);

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2017-12-31T12:00:00Z",
    "parameters": [],
    "provider_unique_id": "",
    "provider_id": "5678"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($identity, JSON_PRETTY_PRINT));
    }
}
