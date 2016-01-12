<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Crypto;

/**
 * Asymmetric decryption using openssl
 */
class Decrypter
{
    // OPENSSL_CIPHER_AES_128_CBC
    const CIPHER = 'aes-128-cbc';

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @param string $privateKey
     */
    public function __construct($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @param string $encrypted
     *
     * @return string|null
     */
    public function decrypt($encrypted)
    {
        if (!$encrypted) {
            return null;
        }

        $exploded = explode('|', $encrypted);
        if (count($exploded) !== 2) {
            return null;
        }

        $sealed = base64_decode($exploded[0]);
        $key = base64_decode($exploded[1]);

        if (!$sealed || !$key) {
            return null;
        }

        openssl_open($sealed, $unencrypted, $key, $this->privateKey);

        if (!is_string($unencrypted)) {
            return null;
        }

        return $unencrypted;
    }
}
