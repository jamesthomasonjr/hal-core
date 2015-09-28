<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $application = new Application;

        $this->assertSame(null, $application->id());
        $this->assertSame(null, $application->key());
        $this->assertSame(null, $application->name());
        $this->assertSame('', $application->githubOwner());
        $this->assertSame('', $application->githubRepo());
        $this->assertSame('', $application->email());
        $this->assertSame(null, $application->group());
    }

    public function testProperties()
    {
        $group = new Group;

        $application = (new Application)
            ->withId(1234)
            ->withKey('app-ident')
            ->withName('My Test App')
            ->withGithubOwner('web-core')
            ->withGithubRepo('hal-core')
            ->withEmail('email@quickenloans.com')
            ->withGroup($group);

        $this->assertSame(1234, $application->id());
        $this->assertSame('app-ident', $application->key());
        $this->assertSame('My Test App', $application->name());
        $this->assertSame('web-core', $application->githubOwner());
        $this->assertSame('hal-core', $application->githubRepo());
        $this->assertSame('email@quickenloans.com', $application->email());
        $this->assertSame($group, $application->group());
    }

    public function testSerialization()
    {
        $group = (new Group)
            ->withId(5678);

        $application = (new Application)
            ->withId(1234)
            ->withKey('app-ident')
            ->withName('My Test App')
            ->withGithubOwner('web-core')
            ->withGithubRepo('hal-core')
            ->withEmail('email@quickenloans.com')
            ->withGroup($group);

        $expected = <<<JSON
{
    "id": 1234,
    "identifier": "app-ident",
    "name": "My Test App",
    "githubOwner": "web-core",
    "githubRepo": "hal-core",
    "email": "email@quickenloans.com",
    "group": 5678
}
JSON;

        $this->assertSame($expected, json_encode($application, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $application = new Application;

        $expected = <<<JSON
{
    "id": null,
    "identifier": null,
    "name": null,
    "githubOwner": "",
    "githubRepo": "",
    "email": "",
    "group": null
}
JSON;

        $this->assertSame($expected, json_encode($application, JSON_PRETTY_PRINT));
    }
}
