<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

class Deployment implements JsonSerializable
{
    /**
     * @type int
     */
    protected $id;

    /**
     * @type string
     */
    protected $name;
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
     * For S3
     *
     * The S3 bucket name
     *
     * @type string
     */
    protected $s3bucket;

    /**
     * For S3
     *
     * The S3 file name. If blank, the push id will be used.
     *
     * @type string
     */
    protected $s3file;

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

    /**
     * @type Credential|null
     */
    protected $credential;

    public function __construct()
    {
        $this->id = null;
        $this->name = '';
        $this->url = '';

        $this->path = null;
        $this->ebEnvironment = null;
        $this->ec2Pool = null;

        $this->s3bucket = null;
        $this->s3file = null;

        $this->application = null;
        $this->server = null;

        $this->credential = null;
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
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function url()
    {
        return $this->url;
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
     * @return string
     */
    public function s3bucket()
    {
        return $this->s3bucket;
    }

    /**
     * @return string
     */
    public function s3file()
    {
        return $this->s3file;
    }

    /**
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return Server|null
     */
    public function server()
    {
        return $this->server;
    }

    /**
     * @return Credential|null
     */
    public function credential()
    {
        return $this->credential;
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
     * @param string $url
     *
     * @return self
     */
    public function withUrl($url)
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
    public function withEBEnvironment($ebEnvironment)
    {
        $this->ebEnvironment = $ebEnvironment;
        return $this;
    }

    /**
     * @param string $ec2Pool
     *
     * @return self
     */
    public function withEC2Pool($ec2Pool)
    {
        $this->ec2Pool = $ec2Pool;
        return $this;
    }

    /**
     * @param string $s3bucket
     *
     * @return self
     */
    public function withS3Bucket($s3bucket)
    {
        $this->s3bucket = $s3bucket;
        return $this;
    }

    /**
     * @param string $s3file
     *
     * @return self
     */
    public function withS3File($s3file)
    {
        $this->s3file = $s3file;
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
     * @param Credential|null $credential
     *
     * @return self
     */
    public function withCredential(Credential $credential = null)
    {
        $this->credential = $credential;
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
            'url' => $this->url(),

            'path' => $this->path(),

            'ebEnvironment' => $this->ebEnvironment(),

            'ec2Pool' => $this->ec2Pool(),

            's3bucket' => $this->s3bucket(),
            's3file' => $this->s3file(),

            'application' => $this->application() ? $this->application()->id() : null,
            'server' => $this->server() ? $this->server()->id() : null,
            'credential' => $this->credential() ? $this->credential()->id() : null,
        ];

        return $json;
    }
}
