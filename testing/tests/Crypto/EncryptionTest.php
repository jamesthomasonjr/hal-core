<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Crypto;

use PHPUnit_Framework_TestCase;

use const Sodium\CRYPTO_SECRETBOX_KEYBYTES;

class EncryptionTest extends PHPUnit_Framework_TestCase
{
    public $unencrypted;
    public $encrypted;
    public $key;

    public function setUp()
    {
        $this->unencrypted = <<<DATA
Vivamus elit dui, gravida eu pulvinar non, volutpat in risus. Proin mattis nibh sit amet magna commodo, sed maximus metus ultricies.
DATA;
        $this->encrypted = <<<DATA
wCVfsNXVi6mm4vzY9KTSNCVPzTnln9NRRBtF9VquVxy844+Vgj/3NNukPW6Vu9FghkI9sOT+aPv1rKkPstqa9ZZA9EcEalRwKSudcYQfpeVcsjmmdkldOup4ds95t1xl+GcZdzRHGhPZdxh1re90aD5jPfc1fGPI7vl1G5rDaZrJT7lOosBUB2b5P/8AA1uHBnsJIsYt/uXE+U6tIykttfjjacQSh4rQeR993A==
DATA;

        $this->key = base64_decode('zlAIQXgUtQFuUMfixSd2FEMu4iXOhXm+TxS82TY9hGo=');
    }

    public function testInvalidKeyThrowsException()
    {
        $this->expectException(CryptoException::class);
        $this->expectExceptionMessage('Key provided to Libsodium encryption is invalid. Secret key must be 32 bytes.');

        $encryption = new Encryption('abcd');
    }

    public function testEncryptWithNonStringInputReturnsNull()
    {
        $encryption = new Encryption($this->key);

        $output = $encryption->encrypt(['test']);

        $this->assertSame(null, $output);
    }

    public function testEncryptWithNonStringScalarInputDecryptsToString()
    {
        $input = 1234;
        $output = '1234';

        $encryption = new Encryption($this->key);

        $encrypted = $encryption->encrypt($input);
        $decrypted = $encryption->decrypt($encrypted);

        $this->assertSame($output, $decrypted);
    }

    public function testInvalidDecryptableReturnsNull()
    {
        $encryption = new Encryption($this->key);

        $decrypted = $encryption->decrypt('smallstring');

        $this->assertSame(null, $decrypted);
    }

    public function testEncrypt()
    {
        $encryption = new Encryption($this->key);

        $encrypted = $encryption->encrypt($this->unencrypted);

        $this->assertSame(true, is_string($encrypted));
        $this->assertSame(232, strlen($encrypted));
    }

    public function testDecrypt()
    {
        $encryption = new Encryption($this->key);

        $decrypted = $encryption->decrypt($this->encrypted);

        $this->assertSame($decrypted, $this->unencrypted);
    }
}
