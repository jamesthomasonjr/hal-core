<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use DateTime;
use JsonSerializable;
use QL\MCP\Common\Time\TimePoint;

/**
 * Build|Push Event Log Entity
 */
class EventLog implements JsonSerializable
{
    /**
     * @type string
     */
    protected $id;

    /**
     * @type string
     */
    protected $event;

    /**
     * @type int
     */
    protected $order;

    /**
     * @type TimePoint
     */
    protected $created;

    /**
     * @type string
     */
    protected $message;
    protected $status;

    /**
     * The build for this event, optional.
     *
     * @type Build
     */
    protected $build;

    /**
     * The push for this event, optional.
     *
     * @type Push
     */
    protected $push;

    /**
     * The data associated with the event
     *
     * @type array
     */
    protected $data;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->event = '';
        $this->order = 0;
        $this->created = null;

        $this->message = '';
        $this->status = '';

        $this->build = null;
        $this->push = null;

        $this->data = [];
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
    public function event()
    {
        return $this->event;
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
     * @return string
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * @return Build|null
     */
    public function build()
    {
        return $this->build;
    }

    /**
     * @return Push|null
     */
    public function push()
    {
        return $this->push;
    }

    /**
     * @return array|null
     */
    public function data()
    {
        return $this->data;
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
     * @param string $status
     *
     * @return self
     */
    public function withEvent($event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @param int $order
     *
     * @return self
     */
    public function withOrder($order)
    {
        $this->order = $order;
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
     * @param Build $build
     *
     * @return self
     */
    public function withBuild(Build $build)
    {
        $this->build = $build;
        return $this;
    }

    /**
     * @param Push $push
     *
     * @return self
     */
    public function withPush(Push $push)
    {
        $this->push = $push;
        return $this;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public function withData(array $data)
    {
        $this->data = $data;
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

            'event' => $this->event(),
            'order' => $this->order(),
            'message' => $this->message(),
            'status' => $this->status(),

            'build' => $this->build() ? $this->build()->id() : null,
            'push' => $this->push() ? $this->push()->id() : null,

            // 'data' => $this->data(),
            'data' => '**DATA**',
        ];

        return $json;
    }
}
