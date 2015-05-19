<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use MCP\DataType\HttpUrl;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

class Deployment implements JsonSerializable
{
    /**
     * @type int
     */
    protected $id;

    /**
     * @type HttpUrl|null
     */
    protected $url;

    /**
     * For RSYNC
     *
     * The path
     *
     * @type string
     */
    protected $path;

    /**
     * For ELASTIC BEANSTALK
     *
     * The EB environment ID
     *
     * @type string
     */
    protected $ebEnvironment;

    /**
     * For EC2
     *
     * The EC2 autoscaling pool. EC2 Instances must be tagged with the pool they belong to. This is how HAL knows where to put code.
     *
     * @type string
     */
    protected $ec2Pool;

    /**
     * @type Repository
     */
    protected $repository;

    /**
     * For RSYNC
     *
     * @type Server|null
     */
    protected $server;

    public function __construct()
    {
        $this->id = null;
        $this->url = null;

        $this->path = null;
        $this->ebEnvironment = null;
        $this->ec2Pool = null;

        $this->repository = null;
        $this->server = null;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $ebEnvironment
     */
    public function setEbEnvironment($ebEnvironment)
    {
        $this->ebEnvironment = $ebEnvironment;
    }

    /**
     * @return string
     */
    public function getEbEnvironment()
    {
        return $this->ebEnvironment;
    }

    /**
     * @param string $ec2Pool
     */
    public function setEc2Pool($ec2Pool)
    {
        $this->ec2Pool = $ec2Pool;
    }

    /**
     * @return string
     */
    public function getEc2Pool()
    {
        return $this->ec2Pool;
    }

    /**
     * @param HttpUrl $url
     */
    public function setUrl(HttpUrl $url)
    {
        $this->url = $url;
    }

    /**
     * @return HttpUrl|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param Repository $repository
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param Server $server
     */
    public function setServer(Server $server)
    {
        $this->server = $server;
    }

    /**
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->getId(),

            'path' => $this->getPath(),
            'ebEnvironment' => $this->getEbEnvironment(),
            'ec2Pool' => $this->getEc2Pool(),

            'url' => $this->getUrl() ? $this->getUrl()->asString() : null,
            'repository' => $this->getRepository() ? $this->getRepository()->getId() : null,
            'server' => $this->getServer() ? $this->getServer()->getId() : null
        ];

        return $json;
    }
}
