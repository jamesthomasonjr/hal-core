<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use JsonSerializable;
use MCP\DataType\Time\TimePoint;
use QL\Hal\Core\Type\EnumType\ProcessStatusEnum;

class Process implements JsonSerializable
{
    /**
     * @type string
     */
    protected $id;

    /**
     * @type TimePoint
     */
    protected $created;

    /**
     * @type User
     */
    protected $user;

    /**
     * @type string
     */
    protected $status;
    protected $message;

    /**
     * @type array
     */
    protected $context;

    /**
     * @type string
     */
    protected $parent;
    protected $parentType;

    /**
     * @type string
     */
    protected $child;
    protected $childType;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->created = null;
        $this->user = null;

        $this->status = ProcessStatusEnum::getDefault();
        $this->message = '';
        $this->context = [];

        $this->parent = $this->parentType = '';
        $this->child = $this->childType = '';
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return TimePoint|null
     */
    public function created()
    {
        return $this->created;
    }

    /**
     * @return User
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
    public function context()
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function parentType()
    {
        return $this->parentType;
    }

    /**
     * @return string
     */
    public function child()
    {
        return $this->child;
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
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param TimePoint|null $created
     *
     * @return self
     */
    public function withCreated(TimePoint $created = null)
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
        $this->status = $status;
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
     * @param array $context
     *
     * @return self
     */
    public function withContext(array $context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @param string|Build|Push $parent
     *
     * @return self
     */
    public function withParent($parent)
    {
        if ($parent instanceof Build) {
            $this->withParentType('Build');
            $parent = $parent->id();

        } elseif ($parent instanceof Push) {
            $this->withParentType('Push');
            $parent = $parent->id();
        }

        $this->parent = $parent;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function withParentType($type)
    {
        $this->parentType = $type;
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

        } elseif ($child instanceof Push) {
            $this->withChildType('Push');
            $child = $child->id();
        }

        $this->child = $child;
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
            'user' => $this->user() ? $this->user()->id() : null,

            'status' => $this->status(),
            'message' => $this->message(),
            'context' => $this->context(),

            'parent' => $this->parent(),
            'parentType' => $this->parentType(),

            'child' => $this->child(),
            'childType' => $this->childType()
        ];

        return $json;
    }
}
