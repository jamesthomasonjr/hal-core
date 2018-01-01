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
use QL\MCP\Common\Time\TimePoint;

class CredentialTest extends TestCase
{
    public function testDefaultValues()
    {
        $credential = new Credential;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $credential->id());
        $this->assertInstanceOf(TimePoint::class, $credential->created());

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
        $credential = (new Credential('X1234', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC')))
            ->withName('my secret credentials')
            ->withType('aws_static')
            ->withIsInternal(true);

        $expected = <<<JSON_TEXT
{
    "id": "X1234",
    "created": "2018-01-01T12:00:00Z",
    "name": "my secret credentials",
    "type": "aws_static",
    "isInternal": true,
    "details": {
        "key": ""
    },
    "application_id": null,
    "organization_id": null,
    "environment_id": null
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($credential, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $credential = new Credential('1', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC'));

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2018-01-01T12:00:00Z",
    "name": "",
    "type": "aws_static",
    "isInternal": false,
    "details": {
        "key": ""
    },
    "application_id": null,
    "organization_id": null,
    "environment_id": null
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($credential, JSON_PRETTY_PRINT));
    }

    public function testInvalidEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid CredentialEnum option.');

        $credential = new Credential('id');
        $credential->withType('derp');
    }
}
