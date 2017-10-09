<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\Credential;

use PHPUnit\Framework\TestCase;

class PrivateKeyCredentialTest extends TestCase
{
    public function testDefaultValues()
    {
        $cred = new PrivateKeyCredential;

        $this->assertSame('', $cred->username());
        $this->assertSame('', $cred->path());
        $this->assertSame('', $cred->file());
    }

    public function testProperties()
    {
        $cred = new PrivateKeyCredential('user', 'path/to/key', 'actual_key');

        $this->assertSame('user', $cred->username());
        $this->assertSame('path/to/key', $cred->path());
        $this->assertSame('actual_key', $cred->file());
    }

    public function testSerialization()
    {
        $cred = new PrivateKeyCredential('user', 'path/to/key', 'actual_key');

        $expected = <<<JSON
{
    "username": "user",
    "path": "path\/to\/key"
}
JSON;

        $this->assertSame($expected, json_encode($cred, JSON_PRETTY_PRINT));
    }
}
