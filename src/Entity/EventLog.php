<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use DateTime;
use JsonSerializable;
use MCP\DataType\Time\TimePoint;

/**
 * Build|Push Event Log Entity
 */
class EventLog implements JsonSerializable
{
    /**
     * The event log id
     *
     * @var string
     */
    protected $id;

    /**
     * The event name
     *
     * @var string
     */
    protected $event;

    /**
     * The event order
     *
     * @var string
     */
    protected $order;

    /**
     * When the log was created
     *
     * @var Timepoint
     */
    protected $created;

    /**
     * The log message
     *
     * @var string
     */
    protected $message;

    /**
     * The log status
     *
     * @var string
     */
    protected $status;

    /**
     * The build for this event, optional.
     *
     * @var Build
     */
    protected $build;

    /**
     * The push for this event, optional.
     *
     * @var Push
     */
    protected $push;

    /**
     * The data associated with the event
     *
     * @var array
     */
    protected $data;

    public function __construct()
    {
        $this->id = null;
        $this->event = null;
        $this->created = null;

        $this->message = null;
        $this->status = null;

        $this->build = null;
        $this->push = null;

        $this->data = null;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return TimePoint
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return Build|null
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * @return Push|null
     */
    public function getPush()
    {
        return $this->push;
    }

    /**
     * @return array|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $status
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @param TimePoint $created
     */
    public function setCreated(TimePoint $created)
    {
        $this->created = $created;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param Build $build
     */
    public function setBuild(Build $build)
    {
        $this->build = $build;
    }

    /**
     * @param Push $push
     */
    public function setPush(Push $push)
    {
        $this->push = $push;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->getId(),

            'created' => $this->getCreated() ? $this->getCreated()->format(DateTime::RFC3339, 'UTC') : null,

            'event' => $this->getEvent(),
            'order' => $this->getOrder(),
            'message' => $this->getMessage(),
            'status' => $this->getStatus(),

            'build' => $this->getBuild() ? $this->getBuild()->getId() : null,
            'push' => $this->getPush() ? $this->getPush()->getId() : null,

            // 'data' => $this->getData(),
            'data' => '**DATA**',
        ];

        return $json;
    }
}
