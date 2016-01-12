<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Crypto;

/**
 * Convenience factory to handle encryption
 */
class CryptoFactory
{
    /**
     * @var callable
     */
    private $fileLoader;

    /**
     * @var string|null
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
     * @return Decrypter
     */
    public function getAsymmetricDecrypter()
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
