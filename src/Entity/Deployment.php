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

class Deployment implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;
    protected $url;

    /**
     * For RSYNC
     *
     * The path
     *
     * @var string
     */
    protected $path;

    /**
     * For CODE DEPLOY
     *
     * The CD application name
     * The CD deployment group
     * The CD configuration
     *
     * @var string
     */
    protected $cdName;
    protected $cdGroup;
    protected $cdConfiguration;

    /**
     * For ELASTIC BEANSTALK
     *
     * The EB application name
     * The EB environment ID
     *
     * @var string
     */
    protected $ebName;
    protected $ebEnvironment;

    /**
     * For S3
     * For ELASTIC BEANSTALK
     * For CODEDEPLOY
     *
     * The S3 bucket name
     *
     * @var string
     */
    protected $s3bucket;

    /**
     * For S3
     *
     * The S3 file name. If blank, the push id will be used.
     *
     * @var string
     */
    protected $s3file;

    /**
     * @var Application
     */
    protected $application;

    /**
     * @var Server|null
     */
    protected $server;

    /**
     * @var Credential|null
     */
    protected $credential;

    /**
     * @var Push|null
     */
    protected $push;

    public function __construct()
    {
        $this->id = null;
        $this->name = '';
        $this->url = '';

        $this->path = null;

        $this->cdName = null;
        $this->cdGroup = null;
        $this->cdConfiguration = null;

        $this->ebName = null;
        $this->ebEnvironment = null;

        $this->s3bucket = null;
        $this->s3file = null;

        $this->application = null;
        $this->server = null;

        $this->credential = null;
        $this->push = null;
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
    public function cdName()
    {
        return $this->cdName;
    }

    /**
     * @return string
     */
    public function cdGroup()
    {
        return $this->cdGroup;
    }

    /**
     * @return string
     */
    public function cdConfiguration()
    {
        return $this->cdConfiguration;
    }

    /**
     * @return string
     */
    public function ebName()
    {
        return $this->ebName;
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
     * @return Push|null
     */
    public function push()
    {
        return $this->push;
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
     * @param string $cdName
     *
     * @return self
     */
    public function withCDName($cdName)
    {
        $this->cdName = $cdName;
        return $this;
    }

    /**
     * @param string $cdGroup
     *
     * @return self
     */
    public function withCDGroup($cdGroup)
    {
        $this->cdGroup = $cdGroup;
        return $this;
    }

    /**
     * @param string $cdConfiguration
     *
     * @return self
     */
    public function withCDConfiguration($cdConfiguration)
    {
        $this->cdConfiguration = $cdConfiguration;
        return $this;
    }

    /**
     * @param string $ebName
     *
     * @return self
     */
    public function withEBName($ebName)
    {
        $this->ebName = $ebName;
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
     * @param Push|null $push
     *
     * @return self
     */
    public function withPush(Push $push = null)
    {
        $this->push = $push;
        return $this;
    }

    /**
     * Format a pretty name for the deployment
     *
     * @param bool $withDetails
     *
     * @return string
     */
    public function formatPretty($withDetails = false)
    {
        if ($this->name()) {
            return $this->name();
        }

        if (!$this->server()) {
            return 'Unknown';
        }

        if ($withDetails) {
            $type = $this->server()->type();

            if ($type === ServerEnum::TYPE_EB) {
                return sprintf('EB (%s)', $this->ebEnvironment());

            } elseif ($type === ServerEnum::TYPE_S3) {
                return sprintf('S3 (%s)', $this->s3bucket());

            } elseif ($type === ServerEnum::TYPE_CD) {
                return sprintf('CD (%s)', $this->cdGroup());
            }
        }

        return $this->server()->formatPretty();
    }

    /**
     * Format a meta details
     *
     * @return string
     */
    public function formatMeta()
    {
        if (!$this->server()) {
            return 'Unknown';
        }

        $type = $this->server()->type();

        if ($type === ServerEnum::TYPE_EB) {
            return $this->ebEnvironment();

        } elseif ($type === ServerEnum::TYPE_S3) {
            $s3 = $this->s3bucket();
            if ($file = $this->s3file()) {
                $s3 = sprintf('%s/%s', $s3, $file);
            }

            return $s3;

        } elseif ($type === ServerEnum::TYPE_CD) {
            return $this->cdGroup();
        }

        return $this->path();
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

            'cdName' => $this->cdName(),
            'cdGroup' => $this->cdGroup(),
            'cdConfiguration' => $this->cdConfiguration(),

            'ebName' => $this->ebName(),
            'ebEnvironment' => $this->ebEnvironment(),

            's3bucket' => $this->s3bucket(),
            's3file' => $this->s3file(),

            'application' => $this->application() ? $this->application()->id() : null,
            'server' => $this->server() ? $this->server()->id() : null,
            'credential' => $this->credential() ? $this->credential()->id() : null,
            'push' => $this->push() ? $this->push()->id() : null,
        ];

        return $json;
    }
}
