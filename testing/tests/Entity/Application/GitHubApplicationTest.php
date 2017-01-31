<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\Application;

use PHPUnit_Framework_TestCase;

class GitHubApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $app = new GitHubApplication;

        $this->assertSame('', $app->owner());
        $this->assertSame('', $app->repository());
    }

    public function testProperties()
    {
        $app = new GitHubApplication('testowner', 'testrepo');

        $this->assertSame('testowner', $app->owner());
        $this->assertSame('testrepo', $app->repository());
    }

    public function testSerialization()
    {
        $app = new GitHubApplication('testowner', 'testrepo');

        $expected = <<<JSON
{
    "owner": "testowner",
    "repository": "testrepo"
}
JSON;

        $this->assertSame($expected, json_encode($app, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $app = new GitHubApplication;

        $expected = <<<JSON
{
    "owner": "",
    "repository": ""
}
JSON;

        $this->assertSame($expected, json_encode($app, JSON_PRETTY_PRINT));
    }
}
