<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\Credential;

class PrivateKeyCredential
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $file;

    /**
     * @param string $username
     * @param string $path
     * @param string $file
     */
    public function __construct($username = '', $path = '', $file = '')
    {
        $this->username = $username;

        $this->path = $path;
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * Encrypted
     *
     * @return string
     */
    public function file()
    {
        return $this->file;
    }

    /**
     * @param string $path
     *
     * @return self
     */
    public function withPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $file
     *
     * @return self
     */
    public function withFile($file)
    {
        $this->file = $file;
        return $this;
    }
}
