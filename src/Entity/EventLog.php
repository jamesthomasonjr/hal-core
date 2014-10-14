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
    private $id;

    /**
     *  The event name
     *
     *  @var string
     *  @Column(name="Event", type="eventenum")
     */
    private $event;

    /**
     * When the log was created
     *
     * @var Timepoint
     * @Column(name="EventLogCreated", type="timepoint")
     */
    private $created;

    /**
     * The build for this event, optional.
     *
     * @var Build
     * @ManyToOne(targetEntity="Build", inversedBy="logs")
     * @JoinColumn(name="BuildId", referencedColumnName="BuildId")
     */
    private $build;

    /**
     * The push for this event, optional.
     *
     * @var Push
     * @ManyToOne(targetEntity="Push", inversedBy="logs")
     * @JoinColumn(name="PushId", referencedColumnName="PushId")
     */
    private $push;

    /**
     * The data associated with the event
     *
     * @var string
     * @Column(name="EventLogData", type="blob", length=10485760)
     */
    private $data;

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
