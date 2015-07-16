<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Credential;

class AWSCredential
{
    /**
     * @type string
     */
    protected $key;

    /**
     * @type string
     */
    protected $secret;

    /**
     * @param string $key
     * @param string $secret
     */
    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Encrypted
     *
     * @return string
     */
    public function secret()
    {
        return $this->secret;
    }
}
