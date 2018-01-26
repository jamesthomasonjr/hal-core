<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Entity\Application\GitHubApplication;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class ApplicationTest extends TestCase
{
    public function testDefaultValues()
    {
        $application = new Application;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $application->id());
        $this->assertInstanceOf(TimePoint::class, $application->created());

        $this->assertSame('', $application->name());
        $this->assertSame(false, $application->isDisabled());

        $this->assertSame(null, $application->organization());
        $this->assertSame(null, $application->provider());
    }

    public function testProperties()
    {
        $org = new Organization;

        $application = (new Application('1234'))
            ->withName('My Test App')
            ->withParameter('vcs.git.owner', 'hal-platform')
            ->withParameter('vcs.git.repo', 'hal-core')
            ->withOrganization($org);

        $this->assertSame('1234', $application->id());
        $this->assertSame('My Test App', $application->name());
        $this->assertSame('hal-platform', $application->parameter('vcs.git.owner'));
        $this->assertSame('hal-core', $application->parameter('vcs.git.repo'));
        $this->assertSame($org, $application->organization());
    }

    public function testSerialization()
    {
        $org = new Organization('5678');

        $application = (new Application('1234', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC')))
            ->withName('My Test App')
            ->withParameter('vcs.git.owner', 'hal-platform')
            ->withParameter('vcs.git.repo', 'hal-core')
            ->withOrganization($org);

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2018-01-01T12:00:00Z",
    "name": "My Test App",
    "is_disabled": false,
    "parameters": {
        "vcs.git.owner": "hal-platform",
        "vcs.git.repo": "hal-core"
    },
    "provider_id": null,
    "organization_id": "5678"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($application, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $application = new Application('1', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC'));

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2018-01-01T12:00:00Z",
    "name": "",
    "is_disabled": false,
    "parameters": [],
    "provider_id": null,
    "organization_id": null
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($application, JSON_PRETTY_PRINT));
    }
}
