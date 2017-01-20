<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Type\GroupEnum;
use Hal\Core\Utility\EntityIDTrait;
use JsonSerializable;

class Target implements JsonSerializable
{
    use EntityIDTrait;

    const PARAM_GROUP = 'group';            // cd
    const PARAM_CONFIG = 'configuration';   // cd

    const PARAM_APP = 'application';        // eb, cd
    const PARAM_ENV = 'environment';        // eb

    const PARAM_BUCKET = 'bucket';          // s3, cd, eb
    const PARAM_PATH = 'path';              // s3, cd, eb, rsync
    const PARAM_CONTEXT = 'context';        // script

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;
    protected $url;

    /**
     * @var Application
     */
    protected $application;

    /**
     * @var Group|null
     */
    protected $group;

    /**
     * @var Credential|null
     */
    protected $credential;

    /**
     * Current release deployed to this target.
     *
     * @var Release|null
     */
    protected $release;

    /**
     * Specific parameters for this target.
     *
     * Such as server path for rsync-based groups.
     *
     * @var array
     */
    protected $parameters;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id ?: $this->generateEntityID();

        $this->name = '';
        $this->url = '';

        $this->application = null;
        $this->group = null;

        $this->credential = null;
        $this->release = null;

        $this->parameters = [];
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
    public function url()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * Get a parameter from the target details.
     *
     * @param string $name
     *
     * @return string
     */
    public function parameter($name)
    {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }

        return null;
    }

    /**
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return Group|null
     */
    public function group()
    {
        return $this->group;
    }

    /**
     * @return Credential|null
     */
    public function credential()
    {
        return $this->credential;
    }

    /**
     * @return Release|null
     */
    public function release()
    {
        return $this->release;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function withID($id)
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
    public function withURL($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return self
     */
    public function withParameter($name, $value)
    {
        $this->parameters[$name] = $value;
        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return self
     */
    public function withParameters(array $parameters)
    {
        $this->parameters = $parameters;
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
     * @param Group|null $group
     *
     * @return self
     */
    public function withGroup(Group $group = null)
    {
        $this->group = $group;
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
     * @param Release|null $release
     *
     * @return self
     */
    public function withRelease(Release $release = null)
    {
        $this->release = $release;
        return $this;
    }

    /**
     * Format a pretty name for the deployment
     *
     * @param bool $onlyGroup Show only group details, no parameters for this target.
     *
     * @return string
     */
    public function format($onlyGroup = false)
    {
        if ($this->name()) {
            return $this->name();
        }

        if (!$this->group()) {
            return 'Unknown';
        }

        if ($onlyGroup) {
            return $this->group()->format();
        }

        switch ($this->group()->type()) {
            case GroupEnum::TYPE_CD:
                return sprintf('CD (%s)', $this->formatParameters());

            case GroupEnum::TYPE_EB:
                return sprintf('EB (%s)', $this->formatParameters());

            case GroupEnum::TYPE_S3:
                return sprintf('S3 (%s)', $this->formatParameters());

            case GroupEnum::TYPE_SCRIPT:
                return sprintf('Script (%s)', $this->formatParameters());

            case GroupEnum::TYPE_RSYNC:
                return sprintf('RSync (%s)', $this->formatParameters());

            default:
                return 'Unknown';
        }
    }

    /**
     * Format parameters into something readable.
     *
     * @return string
     */
    public function formatParameters()
    {
        if (!$this->group()) {
            return 'Unknown';
        }

        switch ($this->group()->type()) {
            case GroupEnum::TYPE_CD:
                return $this->parameter('group') ?: '???';

            case GroupEnum::TYPE_EB:
                return $this->parameter('environment') ?: '???';

            case GroupEnum::TYPE_S3:
                $bucket = $this->parameter('bucket') ?: '???';
                if ($path = $this->parameter('path')) {
                    $bucket = sprintf('%s/%s', $bucket, $path);
                }

                return $bucket;

            case GroupEnum::TYPE_SCRIPT:
                return $this->parameter('context') ?: '???';

            case GroupEnum::TYPE_RSYNC:
                return $this->parameter('path') ?: '???';

            default:
                return 'Unknown';
        }
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

            'parameters' => $this->parameters(),

            'application_id' => $this->application() ? $this->application()->id() : null,
            'group_id' => $this->group() ? $this->group()->id() : null,
            'credential_id' => $this->credential() ? $this->credential()->id() : null,

            'release_id' => $this->release() ? $this->release()->id() : null,
        ];

        return $json;
    }
}
