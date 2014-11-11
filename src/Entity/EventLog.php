<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use MCP\DataType\Time\TimePoint;

/**
 *  Build|Push Event Log Entity
 *
 *  @Entity
 *  @Table(name="EventLogs")
 */
class EventLog
{
    /**
     * The event log id
     *
     * @var int
     * @Id @Column(name="EventLogId", type="integer", unique=true)
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *  The event name
     *
     *  @var string
     *  @Column(name="Event", type="eventenum")
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
     * @var string
     * @Column(name="EventLogData", type="blob", length=10485760)
     */
    protected $data;

    public function __construct()
    {
        $this->id = null;
        $this->event = null;
        $this->recorded = null;

        $this->build = null;
        $this->push = null;

        $this->data = null;
    }
}
