<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity\Credential;

use PHPUnit_Framework_TestCase;

class PrivateKeyCredentialTest extends PHPUnit_Framework_TestCase
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

    public function testPathAndFileCanBeChanged()
    {
        $cred = new PrivateKeyCredential('', 'path/to/key', 'actual_key');

        $this->assertSame('path/to/key', $cred->path());
        $this->assertSame('actual_key', $cred->file());

        $cred
            ->withPath('path2')
            ->withFile('key2');

        $this->assertSame('path2', $cred->path());
        $this->assertSame('key2', $cred->file());
    }
}
