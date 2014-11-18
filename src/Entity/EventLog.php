<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use MCP\DataType\Time\TimePoint;

/**
 * Build|Push Event Log Entity
 *
 * @Entity
 * @Table(name="EventLogs")
 */
class EventLog
{
    /**
     * The event log id
     *
     * @var varchar
     * @Id @Column(name="EventLogId", type="char", length=40)
     */
    protected $id;

    /**
     * The event name
     *
     * @var string
     * @Column(name="Event", type="eventenum")
     */
    protected $event;

    /**
     * When the log was created
     *
     * @var Timepoint
     * @Column(name="EventLogCreated", type="timepoint")
     */
    protected $created;

    /**
     * The log message
     *
     * @var string
     * @Column(name="EventLogMessage", type="string", length=255)
     */
    protected $message;

    /**
     * The log status
     *
     * @var string
     * @Column(name="EventLogStatus", type="eventstatusenum")
     */
    protected $status;

    /**
     * The build for this event, optional.
     *
     * @var Build
     * @ManyToOne(targetEntity="Build", inversedBy="logs")
     * @JoinColumn(name="BuildId", referencedColumnName="BuildId")
     */
    protected $build;

    /**
     * The push for this event, optional.
     *
     * @var Push
     * @ManyToOne(targetEntity="Push", inversedBy="logs")
     * @JoinColumn(name="PushId", referencedColumnName="PushId")
     */
    protected $push;

    /**
     * The data associated with the event
     *
     * @var array
     * @Column(name="EventLogData", type="compressedserialized")
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
}
