<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Crypto;

use PHPUnit_Framework_TestCase;

use Crypt_AES;

/**
 * Generate dummy keys:
 *
 * - openssl genrsa -out KEYNAME.pem 1024
 * - openssl rsa -in KEYNAME.pem -pubout > KEYNAME.pub
 */
class SymmetricDecrypterTest extends PHPUnit_Framework_TestCase
{
    public $data;
    public $keyFile;

    public function setUp()
    {
        $this->data = <<<DATA
Vivamus elit dui, gravida eu pulvinar non, volutpat in risus. Proin mattis nibh sit amet magna commodo, sed maximus metus ultricies.
DATA;
        $this->keyFile = __DIR__ . '/dummy.symmetric_key';
    }

    public function test()
    {
        $key = file_get_contents($this->keyFile);

        $symEnc = new SymmetricEncrypter($key);
        $symDec = new SymmetricDecrypter($key);

        $encrypted = $symEnc->encrypt($this->data);
        $decrypted = $symDec->decrypt($encrypted);

        $this->assertSame($decrypted, $this->data);
    }
}
