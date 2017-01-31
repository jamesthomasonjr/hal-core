<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Type\JobEventStageEnum;
use Hal\Core\Type\JobEventStatusEnum;
use Hal\Core\Utility\EntityIDTrait;
use Hal\Core\Utility\TimeCreatedTrait;
use JsonSerializable;
use QL\MCP\Common\Time\TimePoint;

class JobEvent implements JsonSerializable
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
    protected $stage;
    protected $status;

    /**
     * @var int
     */
    protected $order;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $parent;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param string $id
     * @param TimePoint|null $created
     */
    public function __construct($id = '', TimePoint $created = null)
    {
        $this->id = $id ?: $this->generateEntityID();
        $this->created = $created ?: $this->generateCreatedTime();

        $this->stage = JobEventStageEnum::defaultOption();
        $this->status = JobEventStatusEnum::defaultOption();

        $this->order = 0;

        $this->message = '';
        $this->parent = '';

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
    public function stage()
    {
        return $this->stage;
    }

    /**
     * @return string
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function order()
    {
        return $this->order;
    }

    /**
     * @return TimePoint
     */
    public function created()
    {
        return $this->created;
    }

    /**
     * @return string|null
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * @return string|null
     */
    public function parentID()
    {
        return $this->parent;
    }

    /**
     * @return array
     */
    public function parameters()
    {
        return $this->parameters;
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
     * @param string $stage
     *
     * @return self
     */
    public function withStage($stage)
    {
        $this->stage = JobEventStageEnum::ensureValid($stage);
        return $this;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function withStatus($status)
    {
        $this->status = JobEventStatusEnum::ensureValid($status);
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
     * @param int $order
     *
     * @return self
     */
    public function withOrder($order)
    {
        $this->order = (int) $order;
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
     * @param string $parent
     *
     * @return self
     */
    public function withParentID($parent)
    {
        $this->parent = $parent;
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
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'created' => $this->created(),

            'stage' => $this->stage(),
            'status' => $this->status(),

            'order' => $this->order(),
            'message' => $this->message(),

            'parent_id' => $this->parentID(),

            'parameters' => '**DATA**',
        ];

        return $json;
    }
}
