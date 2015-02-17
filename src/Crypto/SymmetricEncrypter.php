<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Crypto;

/**
 * Symmetric encryption using openssl
 */
class SymmetricEncrypter
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
     * @return string|null
     */
    public function encrypt($data)
    {
        if (!$data || !is_scalar($data)) {
            return null;
        }

        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, self::CIPHER, $this->password, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new CryptoException('An error occured during symmetric encryption.');
        }

        return bin2hex($encrypted) . '|' . bin2hex($iv);
    }
}
