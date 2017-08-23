<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Crypto;

use PHPUnit\Framework\TestCase;

class CryptoFilesystemFactoryTest extends TestCase
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

    public function testInvalidKeyPathThrowsException()
    {
        $this->expectException(CryptoException::class);
        $this->expectExceptionMessage('Path to secret key is invalid. No key file exists');

        $factory = new CryptoFilesystemFactory(__DIR__ . '/nonexistent.key');

        $factory->getCrypto();
    }

    public function testInvalidKeyAtPathThrowsException()
    {
        $this->expectException(CryptoException::class);
        $this->expectExceptionMessage('Secret key at path "' . __DIR__ . '/crypto-invalid.key" is invalid.');

        $factory = new CryptoFilesystemFactory(__DIR__ . '/crypto-invalid.key');

        $factory->getCrypto();
    }

    public function testBuildCryptoFromFilesystemBasedKey()
    {
        $factory = new CryptoFilesystemFactory(__DIR__ . '/crypto.key');

        $crypto = $factory->getCrypto();

        $this->assertInstanceOf(Encryption::class, $crypto);

        $input = 'test';
        $encrypted = $crypto->encrypt('test');

        $this->assertSame($input, $crypto->decrypt($encrypted));

    }
}
