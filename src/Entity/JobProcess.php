<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Type\JobProcessStatusEnum;
use Hal\Core\Utility\EntityIDTrait;
use Hal\Core\Utility\TimeCreatedTrait;
use JsonSerializable;
use QL\MCP\Common\Time\TimePoint;

/**
 * Not a fan of how Parent/Child is handled right not but don't feel like rearchitecting it.
 *
 * Must be revisited though.
 */
class JobProcess implements JsonSerializable
{
    use EntityIDTrait;
    use TimeCreatedTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var TimePoint
     */
    protected $created;

    /**
     * @var string
     */
    protected $status;
    protected $message;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var string
     */
    protected $parentID;

    /**
     * @var string
     */
    protected $childID;
    protected $childType;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * @param string $id
     * @param TimePoint|null $created
     */
    public function __construct($id = '', TimePoint $created = null)
    {
        $this->id = $id ?: $this->generateEntityID();
        $this->created = $created ?: $this->generateCreatedTime();

        $this->status = JobProcessStatusEnum::defaultOption();

        $this->user = null;

        $this->message = '';
        $this->parameters = [];

        $this->parentID = $this->childID = '';
        $this->childType = '';
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return TimePoint
     */
    public function created()
    {
        return $this->created;
    }

    /**
     * @return User|null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function parentID()
    {
        return $this->parentID;
    }

    /**
     * @return string
     */
    public function childID()
    {
        return $this->childID;
    }

    /**
     * @return string
     */
    public function childType()
    {
        return $this->childType;
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
     * @param TimePoint $created
     *
     * @return self
     */
    public function withCreated(TimePoint $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @param User|null $user
     *
     * @return self
     */
    public function withUser(User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function withStatus($status)
    {
        $this->status = JobProcessStatusEnum::ensureValid($status);
        return $this;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function withMessage($message)
    {
        $this->message = $message;
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
     * @param Build|Release|string $parent
     *
     * @return self
     */
    public function withParent($parent)
    {
        if ($parent instanceof Build || $parent instanceof Release) {
            $parent = $parent->id();
        }

        $this->parentID = $parent;
        return $this;
    }

    /**
     * @param string $child
     *
     * @return self
     */
    public function withChild($child)
    {
        if ($child instanceof Build) {
            $this->withParentType('Build');
            $child = $child->id();

        } elseif ($child instanceof Release) {
            $this->withChildType('Release');
            $child = $child->id();
        }

        $this->childID = $child;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function withChildType($type)
    {
        $this->childType = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),
            'created' => $this->created(),
            'status' => $this->status(),

            'user_id' => $this->user() ? $this->user()->id() : null,

            'message' => $this->message(),
            'parameters' => $this->parameters(),

            'parent_id' => $this->parentID(),

            'child_id' => $this->childID(),
            'child_type' => $this->childType()
        ];

        return $json;
    }
}
