<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\Credential;

use PHPUnit\Framework\TestCase;

class AWSStaticCredentialTest extends TestCase
{
    public function testDefaultValues()
    {
        $cred = new AWSStaticCredential;

        $this->assertSame('', $cred->key());
        $this->assertSame('', $cred->secret());
    }

    public function testProperties()
    {
        $cred = new AWSStaticCredential('key1', 'secret2');

        $this->assertSame('key1', $cred->key());
        $this->assertSame('secret2', $cred->secret());
    }

    public function testSerialization()
    {
        $cred = new AWSStaticCredential('key1', 'secret2');

        $expected = <<<JSON
{
    "key": "key1"
}
JSON;

        $this->assertSame($expected, json_encode($cred, JSON_PRETTY_PRINT));
    }
}
