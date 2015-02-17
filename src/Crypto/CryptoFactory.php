<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Crypto;

/**
 * Convenience factory to handle encryption
 */
class CryptoFactory
{
    /**
     * @type callable
     */
    private $fileLoader;

    /**
     * @type string|null
     */
    private $asymKey;

    /**
     * @param string $encryptedKey
     * @param string $symKeyPath
     * @param callable|null $fileLoader
     */
    public function __construct($encryptedKey, $symKeyPath, callable $fileLoader = null)
    {
        $this->encryptedKey = $encryptedKey;
        $this->symKeyPath = $symKeyPath;
        $this->fileLoader = $fileLoader ?: $this->getDefaultFileLoader();
    }

    /**
     * @param string $data
     *
     * @return string|null
     */
    public function getAsymmetricDecrypter($data)
    {
        $symmetricDecrypter = $this->getSymmetricDecrypter();
        $key = $symmetricDecrypter->decrypt($this->encryptedKey);

        return new Decrypter($key);
    }

    /**
     * @return SymmetricDecrypter
     */
    private function getSymmetricDecrypter()
    {
        if (!file_exists($this->symKeyPath)) {
            throw new CryptoException('Path to symmetric key is invalid.');
        }

        $key = call_user_func($this->fileLoader, $this->symKeyPath);
        return new SymmetricDecrypter($key);
    }

    /**
     * @return callable
     */
    protected function getDefaultFileLoader()
    {
        return 'file_get_contents';
    }
}
