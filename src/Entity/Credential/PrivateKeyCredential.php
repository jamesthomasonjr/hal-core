<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Credential;

class PrivateKeyCredential
{
    /**
     * @type string
     */
    protected $username;

    /**
     * @type string
     */
    protected $path;

    /**
     * @type string
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
