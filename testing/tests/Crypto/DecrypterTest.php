<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Crypto;

use PHPUnit_Framework_TestCase;

/**
 * Generate dummy keys:
 *
 * - openssl genrsa -out KEYNAME.pem 1024
 * - openssl rsa -in KEYNAME.pem -pubout > KEYNAME.pub
 */
class DecrypterTest extends PHPUnit_Framework_TestCase
{
    public $privateKey;
    public $publicKey;

    public function setUp()
    {
        $this->privateKey = __DIR__ . '/dummy.pem';
        $this->publicKey = __DIR__ . '/dummy.pub';
    }

    public function test()
    {
        $data = <<<DATA
Vivamus elit dui, gravida eu pulvinar non, volutpat in risus. Proin mattis nibh sit amet magna commodo, sed maximus metus ultricies.
DATA;

        $privateKey = file_get_contents($this->privateKey);
        $publicKey = file_get_contents($this->publicKey);

        $enc = new Encrypter($publicKey);
        $dec = new Decrypter($privateKey);

        $encrypted = $enc->encrypt($data);
        $decrypted = $dec->decrypt($encrypted);

        $this->assertSame($data, $decrypted);
    }
}
