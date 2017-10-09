<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\Credential;

use JsonSerializable;

class AWSStaticCredential implements JsonSerializable
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @param string $key
     * @param string $secret
     */
    public function __construct($key = '', $secret = '')
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

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'key' => $this->key(),
            // 'secret' => $this->secret(),
        ];

        return $json;
    }
}
