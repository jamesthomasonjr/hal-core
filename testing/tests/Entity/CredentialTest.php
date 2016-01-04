<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;
use QL\Hal\Core\Entity\Credential\AWSCredential;
use QL\Hal\Core\Entity\Credential\PrivateKeyCredential;

class CredentialTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $credential = new Credential;

        $this->assertSame('', $credential->id());
        $this->assertSame('', $credential->name());
        $this->assertSame('', $credential->type());

        $this->assertInstanceOf(AWSCredential::CLASS, $credential->aws());
        $this->assertInstanceOf(PrivateKeyCredential::CLASS, $credential->privateKey());
    }

    public function testProperties()
    {
        $aws = new AWSCredential;
        $privateKey = new PrivateKeyCredential;

        $credential = (new Credential)
            ->withId('X1234')
            ->withName('my secret credentials')
            ->withType('aws')
            ->withAWS($aws)
            ->withPrivateKey($privateKey);

        $this->assertSame('X1234', $credential->id());
        $this->assertSame('my secret credentials', $credential->name());
        $this->assertSame('aws', $credential->type());

        $this->assertSame($aws, $credential->aws());
        $this->assertSame($privateKey, $credential->privateKey());
    }

    public function testSerialization()
    {
        $credential = (new Credential)
            ->withId('X1234')
            ->withName('my secret credentials')
            ->withType('aws');

        $expected = <<<JSON
{
    "id": "X1234",
    "type": "aws",
    "name": "my secret credentials"
}
JSON;

        $this->assertSame($expected, json_encode($credential, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $credential = new Credential;

        $expected = <<<JSON
{
    "id": "",
    "type": "",
    "name": ""
}
JSON;

        $this->assertSame($expected, json_encode($credential, JSON_PRETTY_PRINT));
    }
}
