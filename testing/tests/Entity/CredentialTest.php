<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Entity\Credential\AWSCredential;
use Hal\Core\Entity\Credential\PrivateKeyCredential;
use Hal\Core\Type\EnumException;
use PHPUnit_Framework_TestCase;

class CredentialTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $credential = new Credential;

        $this->assertStringMatchesFormat('%x', $credential->id());
        $this->assertSame('', $credential->name());
        $this->assertSame('aws', $credential->type());

        $this->assertInstanceOf(AWSCredential::class, $credential->aws());
        $this->assertInstanceOf(PrivateKeyCredential::class, $credential->privateKey());
    }

    public function testProperties()
    {
        $aws = new AWSCredential;
        $privateKey = new PrivateKeyCredential;

        $credential = (new Credential('X1234'))
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
    "name": "my secret credentials",
    "type": "aws"
}
JSON;

        $this->assertSame($expected, json_encode($credential, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $credential = new Credential('1');

        $expected = <<<JSON
{
    "id": "1",
    "name": "",
    "type": "aws"
}
JSON;

        $this->assertSame($expected, json_encode($credential, JSON_PRETTY_PRINT));
    }

    public function testInvalidEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid credential option.');

        $credential = new Credential('id');
        $credential->withType('derp');
    }
}
