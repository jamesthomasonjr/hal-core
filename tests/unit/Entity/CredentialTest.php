<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Entity\Credential\AWSRoleCredential;
use Hal\Core\Entity\Credential\AWSStaticCredential;
use Hal\Core\Entity\Credential\PrivateKeyCredential;
use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;

class CredentialTest extends TestCase
{
    public function testDefaultValues()
    {
        $credential = new Credential;

        $this->assertStringMatchesFormat('%x', $credential->id());
        $this->assertSame('', $credential->name());
        $this->assertSame('aws_static', $credential->type());
        $this->assertSame(false, $credential->isInternal());

        $this->assertInstanceOf(AWSStaticCredential::class, $credential->details());
    }

    public function testProperties()
    {
        $role = new AWSRoleCredential;
        $static = new AWSStaticCredential;
        $privateKey = new PrivateKeyCredential;

        $credential = (new Credential('X1234'))
            ->withName('my secret credentials')
            ->withDetails($role);

        $this->assertSame('X1234', $credential->id());
        $this->assertSame('my secret credentials', $credential->name());
        $this->assertSame('aws_role', $credential->type());

        $this->assertInstanceOf(AWSRoleCredential::class, $credential->details());

        $credential->withDetails($static);
        $this->assertInstanceOf(AWSStaticCredential::class, $credential->details());

        $credential->withDetails($privateKey);
        $this->assertInstanceOf(PrivateKeyCredential::class, $credential->details());
    }

    public function testSerialization()
    {
        $credential = (new Credential)
            ->withId('X1234')
            ->withName('my secret credentials')
            ->withType('aws_static')
            ->withIsInternal(true);

        $expected = <<<JSON
{
    "id": "X1234",
    "name": "my secret credentials",
    "type": "aws_static",
    "isInternal": true,
    "details": {
        "key": ""
    }
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
    "type": "aws_static",
    "isInternal": false,
    "details": {
        "key": ""
    }
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
