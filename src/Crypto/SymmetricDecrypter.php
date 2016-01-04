<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Crypto;

/**
 * Symmetric decryption using openssl
 */
class SymmetricDecrypter
{
    // OPENSSL_CIPHER_AES_128_CBC
    const CIPHER = 'aes-128-cbc';

    /**
     * @type string
     */
    private $password;

    /**
     * @param string $password
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Encrypt a string and return it, pipeline delimited with the IV.
     *
     * @param string $data
     *
     * @throws CryptoException
     *
     * @return string|null
     */
    public function decrypt($encrypted)
    {
        if (!$encrypted || !is_scalar($encrypted)) {
            return null;
        }

        $exploded = explode('|', $encrypted);
        if (count($exploded) !== 2) {
            throw new CryptoException('Encrypted data and IV must be provided to symmetric decrypter.');
        }

        $encrypted = hex2bin($exploded[0]);
        $iv = hex2bin($exploded[1]);

        $data = openssl_decrypt($encrypted, self::CIPHER, $this->password, OPENSSL_RAW_DATA, $iv);

        if (!$data) {
            throw new CryptoException('An error occured while performing symmetric decryption.');
        }

        return $data;
    }
}
