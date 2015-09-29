<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Kraken\Core\Entity;

use PHPUnit_Framework_TestCase;

class EnvironmentTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $environment = new Environment;

        $this->assertSame('', $environment->id());
        $this->assertSame('', $environment->name());
        $this->assertSame(false, $environment->isProduction());

        $this->assertSame('', $environment->consulServiceURL());
        $this->assertSame('', $environment->consulToken());

        $this->assertSame('', $environment->qksServiceURL());
        $this->assertSame('', $environment->qksEncryptionKey());
        $this->assertSame('', $environment->qksClientID());
        $this->assertSame('', $environment->qksClientSecret());
    }

    public function testProperties()
    {
        $environment = (new Environment('abcd'))
            ->withId('abcdef')
            ->withName('test')
            ->withIsProduction(true)
            ->withConsulServiceURL('http://example.com')
            ->withConsulToken('token')
            ->withQKSServiceURL('http://qks.example.com')
            ->withQKSEncryptionKey('sendingkey')
            ->withQKSClientID('client-id-1234')
            ->withQKSClientSecret('client-secret-abcd');

        $this->assertSame('abcdef', $environment->id());
        $this->assertSame('test', $environment->name());
        $this->assertSame(true, $environment->isProduction());

        $this->assertSame('http://example.com', $environment->consulServiceURL());
        $this->assertSame('token', $environment->consulToken());

        $this->assertSame('http://qks.example.com', $environment->qksServiceURL());
        $this->assertSame('sendingkey', $environment->qksEncryptionKey());
        $this->assertSame('client-id-1234', $environment->qksClientID());
        $this->assertSame('client-secret-abcd', $environment->qksClientSecret());
    }

    public function testSerialization()
    {
        $environment = (new Environment('abcd'))
            ->withId('abcdef')
            ->withName('test')
            ->withIsProduction(true)
            ->withConsulServiceURL('http://example.com')
            ->withConsulToken('token')
            ->withQKSServiceURL('http://qks.example.com')
            ->withQKSEncryptionKey('sendingkey')
            ->withQKSClientID('client-id-1234')
            ->withQKSClientSecret('client-secret-abcd');

        $expected = <<<JSON
{
    "id": "abcdef",
    "name": "test",
    "isProduction": true,
    "consulServiceURL": "http://example.com",
    "qksServiceURL": "http://qks.example.com",
    "qksEncryptionKey": "sendingkey",
    "qksClientID": "client-id-1234"
}
JSON;

        $this->assertSame($expected, json_encode($environment, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function testDefaultSerialization()
    {
        $environment = new Environment;

        $expected = <<<JSON
{
    "id": "",
    "name": "",
    "isProduction": false,
    "consulServiceURL": "",
    "qksServiceURL": "",
    "qksEncryptionKey": "",
    "qksClientID": ""
}
JSON;

        $this->assertSame($expected, json_encode($environment, JSON_PRETTY_PRINT));
    }
}
