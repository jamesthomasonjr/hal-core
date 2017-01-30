<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Crypto;

use const Sodium\CRYPTO_SECRETBOX_KEYBYTES;
use const Sodium\CRYPTO_SECRETBOX_NONCEBYTES;
use function Sodium\crypto_secretbox;
use function Sodium\crypto_secretbox_open;
use function Sodium\randombytes_buf;

/**
 * Symmetric encryption using Libsodium
 *
 * @link https://github.com/jedisct1/libsodium-php
 * @link https://paragonie.com/white-paper/2015-secure-php-data-encryption#secure-cryptographic-storage
 */
class Encryption
{
    const ERR_INVALID_KEY = 'Key provided to Libsodium encryption is invalid. Secret key must be %d bytes.';

    /**
     * @var string
     */
    private $key;

    /**
     * Secret key must be provided as 32 binary bytes.
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;

        if (strlen($this->key) !== CRYPTO_SECRETBOX_KEYBYTES) {
            throw new CryptoException(sprintf(self::ERR_INVALID_KEY, CRYPTO_SECRETBOX_KEYBYTES));
        }
    }

    /**
     * Encrypt a string.
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

        $nonce = randombytes_buf(CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = crypto_secretbox($data, $nonce, $this->key);

        return base64_encode($nonce . $cipher);
    }

    /**
     * Decrypt a string.
     *
     * @param string $data
     *
     * @return string|null
     */
    public function decrypt($data)
    {
        if (!$data || !is_scalar($data)) {
            return null;
        }

        $decoded = base64_decode($data, true);
        if ($decoded === false) {
            return null;
        }

        if (strlen($decoded) < CRYPTO_SECRETBOX_NONCEBYTES) {
            return null;
        }

        $nonce = substr($decoded, 0, CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = substr($decoded, CRYPTO_SECRETBOX_NONCEBYTES);

        $decrypted = crypto_secretbox_open($cipher, $nonce, $this->key);

        if ($decrypted === false) {
            return null;
        }

        return $decrypted;
    }
}
