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

/**
 * Terrible naming! Must be changed!
 *
 * Alternatives:
 *
 * Space, System, Set, Domain, Workspace, Group, Node
 *
 * "Targets are attached to X"
 *
 * - "This target belongs to server XYZ"
 * - "This target is for aws region us-east-1"
 *
 * Other terms and systems:
 *     - Code Deploy Group
 *     - Elastic Beanstalk Environment
 *     - Kubernetes Pod
 */
class Group implements JsonSerializable
{
    use EntityIDTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * - rsync: server hostname
     * - eb: aws region
     * - cd: aws region
     * - s3: aws region
     *
     * - script: not used
     *
     * @var string
     */
    protected $name;

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * @param string $id
     * @param string $type
     */
    public function __construct($id = '', $type = '')
    {
        $this->id = $id ?: $this->generateEntityID();
        $this->type = $type ? GroupEnum::ensureValid($type) : GroupEnum::defaultOption();

        $this->name = '';

        $this->environment = null;
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
     * @return Environment
     */
    public function environment()
    {
        return $this->environment;
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
     * @param string $type
     *
     * @return self
     */
    public function withType($type)
    {
        $this->type = GroupEnum::ensureValid($type);
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
     * Format how the Group is displayed in text.
     *
     * @param bool $onlyType
     *
     * @return string
     */
    public function format($onlyType = true)
    {
        if ($onlyType) {
            return $this->formatWithoutDetails();
        }

        switch ($this->type()) {
            case GroupEnum::TYPE_CD:
                return sprintf('CD (%s)', $this->name());

            case GroupEnum::TYPE_EB:
                return sprintf('EB (%s)', $this->name());

            case GroupEnum::TYPE_S3:
                return sprintf('S3 (%s)', $this->name());

            case GroupEnum::TYPE_SCRIPT:
                return 'Script';

            case GroupEnum::TYPE_RSYNC:
                return sprintf('RSync (%s)', $this->name());

            default:
                return 'Unknown';
        }
    }

    /**
     * @return string
     */
    private function formatWithoutDetails()
    {
        switch ($this->type()) {
            case GroupEnum::TYPE_CD:
                return 'CodeDeploy';

            case GroupEnum::TYPE_EB:
                return 'Elastic Beanstalk';

            case GroupEnum::TYPE_S3:
                return 'S3';

            case GroupEnum::TYPE_SCRIPT:
                return 'Script';

            case GroupEnum::TYPE_RSYNC:
                return 'RSync';

            default:
                return 'Unknown';
        }
    }

    /**
     * Is this group for AWS?
     *
     * @return bool
     */
    public function isAWS()
    {
        return in_array($this->type(), [GroupEnum::TYPE_CD, GroupEnum::TYPE_EB, GroupEnum::TYPE_S3]);
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

            'environment_id' => $this->environment() ? $this->environment()->id() : null,
        ];

        return $json;
    }
}
