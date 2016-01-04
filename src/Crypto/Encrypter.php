<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Crypto;

/**
 * Asymmetric encryption using openssl
 */
class Encrypter
{
    /**
     * @type string
     */
    private $publickey;

    /**
     * @param string $publickey
     */
    public function __construct($publickey)
    {
        $this->publickey = $publickey;
    }

    /**
     * Encrypt a string and return the sealed data (base64) and encrypted key in a pipe-delimited string
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

        openssl_seal($data, $sealed, $encryptedKeys, [$this->publickey]);

        $key = array_pop($encryptedKeys);
        $encrypted = base64_encode($sealed) . '|' . base64_encode($key);

        return $encrypted;
    }
}
