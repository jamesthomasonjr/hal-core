<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Entity\Credential\AWSRoleCredential;
use Hal\Core\Entity\Credential\AWSStaticCredential;
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
     * Signifies a credential is internal and can be edited only by administrators.
     *
     * @var bool
     */
    protected $isInternal;

    /**
     * @var AWSRoleCredential|null
     */
    protected $awsRole;

    /**
     * @var AWSStaticCredential|null
     */
    protected $awsStatic;

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
        $this->isInternal = false;

        $this->awsRole = new AWSRoleCredential;
        $this->awsStatic = new AWSStaticCredential;
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
     * @return bool
     */
    public function isInternal()
    {
        return $this->isInternal;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return AWSRoleCredential|AWSStaticCredentia|PrivateKeyCredential|null
     */
    public function details()
    {
        if ($this->type() === CredentialEnum::TYPE_AWS_ROLE) {
            return $this->awsRole;

        } elseif ($this->type() === CredentialEnum::TYPE_AWS_STATIC) {
            return $this->awsStatic;

        } elseif ($this->type() === CredentialEnum::TYPE_PRIVATEKEY) {
            return $this->privateKey;
        }

        return null;
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
     * @param bool $isInternal
     *
     * @return self
     */
    public function withIsInternal($isInternal)
    {
        $this->isInternal = (bool) $isInternal;
        return $this;
    }

    /**
     * @param AWSRoleCredential|AWSStaticCredential|PrivateKeyCredential $info
     *
     * @return self
     */
    public function withDetails($info)
    {
        if ($info instanceof AWSRoleCredential) {
            $this->withType(CredentialEnum::TYPE_AWS_ROLE);
            $this->awsAssume = $info;
            $this->awsStatic = new AWSStaticCredential;
            $this->privateKey = new PrivateKeyCredential;

        } elseif ($info instanceof AWSStaticCredential) {
            $this->withType(CredentialEnum::TYPE_AWS_STATIC);
            $this->awsAssume = new AWSRoleCredential;
            $this->awsStatic = $info;
            $this->privateKey = new PrivateKeyCredential;

        } elseif ($info instanceof PrivateKeyCredential) {
            $this->withType(CredentialEnum::TYPE_PRIVATEKEY);
            $this->awsAssume = new AWSRoleCredential;
            $this->awsStatic = new AWSStaticCredential;
            $this->privateKey = $info;

        } else {
            $this->withType(CredentialEnum::TYPE_AWS_STATIC);
            $this->awsAssume = new AWSRoleCredential;
            $this->awsStatic = new AWSStaticCredential;
            $this->privateKey = new PrivateKeyCredential;
        }

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
            'isInternal' => $this->isInternal(),

            'details' => $this->details()
        ];

        return $json;
    }
}
