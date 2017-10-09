<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\Credential;

use PHPUnit\Framework\TestCase;

class AWSRoleCredentialTest extends TestCase
{
    public function testDefaultValues()
    {
        $cred = new AWSRoleCredential;

        $this->assertSame('', $cred->account());
        $this->assertSame('', $cred->role());
    }

    public function testProperties()
    {
        $cred = new AWSRoleCredential('123456789', 'role/iam-name');

        $this->assertSame('123456789', $cred->account());
        $this->assertSame('role/iam-name', $cred->role());
    }

    public function testSerialization()
    {
        $cred = new AWSRoleCredential('123456789', 'role/iam-name');

        $expected = <<<JSON
{
    "account": "123456789",
    "role": "role\/iam-name"
}
JSON;

        $this->assertSame($expected, json_encode($cred, JSON_PRETTY_PRINT));
    }
}
