<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Entity\Application\GitHubApplication;
use PHPUnit_Framework_TestCase;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $application = new Application;

        $this->assertStringMatchesFormat('%x', $application->id());
        $this->assertSame('', $application->identifier());
        $this->assertSame('', $application->name());
        $this->assertSame('', $application->gitHub()->owner());
        $this->assertSame('', $application->gitHub()->repository());
        $this->assertSame(null, $application->organization());
    }

    public function testProperties()
    {
        $org = new Organization;

        $application = (new Application('1234'))
            ->withIdentifier('app-ident')
            ->withName('My Test App')
            ->withGitHub(new GitHubApplication('hal-platform', 'hal-core'))
            ->withOrganization($org);

        $this->assertSame('1234', $application->id());
        $this->assertSame('app-ident', $application->identifier());
        $this->assertSame('My Test App', $application->name());
        $this->assertSame('hal-platform', $application->gitHub()->owner());
        $this->assertSame('hal-core', $application->gitHub()->repository());
        $this->assertSame($org, $application->organization());
    }

    public function testSerialization()
    {
        $org = new Organization('5678');

        $application = (new Application)
            ->withID('1234')
            ->withIdentifier('app-ident')
            ->withName('My Test App')
            ->withGitHub(new GitHubApplication('hal-platform', 'hal-core'))
            ->withOrganization($org);

        $expected = <<<JSON
{
    "id": "1234",
    "identifier": "app-ident",
    "name": "My Test App",
    "github": {
        "owner": "hal-platform",
        "repository": "hal-core"
    },
    "organization_id": "5678"
}
JSON;

        $this->assertSame($expected, json_encode($application, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $application = new Application("1");

        $expected = <<<JSON
{
    "id": "1",
    "identifier": "",
    "name": "",
    "github": {
        "owner": "",
        "repository": ""
    },
    "organization_id": null
}
JSON;

        $this->assertSame($expected, json_encode($application, JSON_PRETTY_PRINT));
    }
}
