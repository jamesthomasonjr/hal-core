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
     * @type Application
     */
    protected $application;

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

        $this->application = null;
        $this->server = null;
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
    public function path()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function ebEnvironment()
    {
        return $this->ebEnvironment;
    }

    /**
     * @return string
     */
    public function ec2Pool()
    {
        return $this->ec2Pool;
    }

    /**
     * @return HttpUrl|null
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return Server
     */
    public function server()
    {
        return $this->server;
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
     * @param HttpUrl $url
     *
     * @return self
     */
    public function withUrl(HttpUrl $url)
    {
        $this->url = $url;
        return $this;
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
     * @param string $ebEnvironment
     *
     * @return self
     */
    public function withEbEnvironment($ebEnvironment)
    {
        $this->ebEnvironment = $ebEnvironment;
        return $this;
    }

    /**
     * @param string $ec2Pool
     *
     * @return self
     */
    public function withEc2Pool($ec2Pool)
    {
        $this->ec2Pool = $ec2Pool;
        return $this;
    }

    /**
     * @param Application $application
     *
     * @return self
     */
    public function withApplication(Application $application)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @param Server $server
     *
     * @return self
     */
    public function withServer(Server $server)
    {
        $this->server = $server;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'path' => $this->path(),
            'ebEnvironment' => $this->ebEnvironment(),
            'ec2Pool' => $this->ec2Pool(),

            'url' => $this->url() ? $this->url()->asString() : null,
            'application' => $this->application() ? $this->application()->id() : null,
            'server' => $this->server() ? $this->server()->id() : null
        ];

        return $json;
    }
}
