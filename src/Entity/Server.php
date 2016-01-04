<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use QL\Hal\Core\Type\EnumType\ServerEnum;

class Server implements JsonSerializable
{
    /**
     * @type int
     */
    protected $id;

    /**
     * @type string
     */
    protected $type;

    /**
     * Hostname for rsync servers
     * Region for elasticbeanstalk,ec2,s3 servers
     *
     * @type string
     */
    protected $name;

    /**
     * @type Environment
     */
    protected $environment;

    /**
     * Deployments for the server
     *
     * @type ArrayCollection
     */
    protected $deployments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->type = '';
        $this->name = '';

        $this->environment = null;
        $this->deployments = new ArrayCollection;
    }

    /**
     * @return int
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
     * @return Environment
     */
    public function environment()
    {
        return $this->environment;
    }

    /**
     * @return ArrayCollection
     */
    public function deployments()
    {
        return $this->deployments;
    }

    /**
     * @param int $id
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
     * @param Environment $environment
     *
     * @return self
     */
    public function withEnvironment(Environment $environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * Format a pretty name for the server
     *
     * @return string
     */
    public function formatPretty()
    {
        $type = $this->type();

        if ($type === ServerEnum::TYPE_EB) {
            return sprintf('EB (%s)', $this->name());

        } elseif ($type === ServerEnum::TYPE_EC2) {
            return sprintf('EC2 (%s)', $this->name());

        } elseif ($type === ServerEnum::TYPE_S3) {
            return sprintf('S3 (%s)', $this->name());

        } elseif ($type === ServerEnum::TYPE_CD) {
            return sprintf('CD (%s)', $this->name());
        }

        return $this->name();
    }

    /**
     * Format a human name for the server type
     *
     * @return string
     */
    public function formatHumanType()
    {
        $type = $this->type();

        if ($type === ServerEnum::TYPE_EB) {
            return 'Elastic Beanstalk';

        } elseif ($type === ServerEnum::TYPE_EC2) {
            return 'EC2 Autoscaling Pool';

        } elseif ($type === ServerEnum::TYPE_S3) {
            return 'S3';

        } elseif ($type === ServerEnum::TYPE_CD) {
            return 'CodeDeploy';
        }

        return 'Internal (Rsync)';
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

            'environment' => $this->environment() ? $this->environment()->id() : null,

            // 'deployments' => $this->deployments() ? $this->deployments()->getKeys() : []
        ];

        return $json;
    }
}
