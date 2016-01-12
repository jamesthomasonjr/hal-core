<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use JsonSerializable;
use QL\Hal\Core\Entity\Credential\AWSCredential;
use QL\Hal\Core\Entity\Credential\PrivateKeyCredential;

class Credential implements JsonSerializable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var AWSCredential|null
     */
    protected $aws;

    /**
     * @var PrivateKeyCredential|null
     */
    protected $privateKey;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;

        $this->type = '';
        $this->name = '';

        $this->aws = new AWSCredential;
        $this->privateKey = new PrivateKeyCredential;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return AWSCredential|null
     */
    public function aws()
    {
        return $this->aws;
    }

    /**
     * @return PrivateKeyCredential|null
     */
    public function privateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function withType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function withName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param AWSCredential $aws
     *
     * @return self
     */
    public function withAWS(AWSCredential $aws)
    {
        $this->aws = $aws;
        return $this;
    }

    /**
     * @param PrivateKeyCredential $key
     *
     * @return self
     */
    public function withPrivateKey(PrivateKeyCredential $privateKey)
    {
        $this->privateKey = $privateKey;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'type' => $this->type(),
            'name' => $this->name(),

            // 'aws' => $this->aws(),
            // 'privateKey' => $this->privateKey(),
        ];

        return $json;
    }
}
