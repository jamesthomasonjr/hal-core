<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Entity\Credential\AWSCredential;
use Hal\Core\Entity\Credential\PrivateKeyCredential;
use Hal\Core\Type\CredentialEnum;
use Hal\Core\Utility\EntityIDTrait;
use JsonSerializable;

class Credential implements JsonSerializable
{
    use EntityIDTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;
    protected $type;

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
        $this->id = $id ?: $this->generateEntityID();

        $this->name = '';
        $this->type = CredentialEnum::defaultOption();

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
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
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
    public function withID($id)
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
        $this->type = CredentialEnum::ensureValid($type);
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

            'name' => $this->name(),
            'type' => $this->type(),

            // 'aws' => $this->aws(),
            // 'privateKey' => $this->privateKey(),
        ];

        return $json;
    }
}
