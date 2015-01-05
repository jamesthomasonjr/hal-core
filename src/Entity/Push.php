<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use MCP\DataType\Time\TimePoint;

/**
 * @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\PushRepository")
 * @Table(name="Pushes")
 */
class Push
{
    /**
     * The push id
     *
     * @var string
     * @Id @Column(name="PushId", type="string", length=40, unique=true)
     */
    protected $id;

    /**
     * The push created time
     *
     * @var TimePoint|null;
     * @Column(name="PushCreated", type="timepoint", nullable=true)
     */
    protected $created;

    /**
     * The push start time
     *
     * @var TimePoint|null;
     * @Column(name="PushStart", type="timepoint", nullable=true)
     */
    protected $start;

    /**
     * The push end time
     *
     * @var TimePoint;
     * @Column(name="PushEnd", type="timepoint", nullable=true)
     */
    protected $end;

    /**
     * The push status
     *
     * @var string
     * @Column(name="PushStatus", type="pushstatusenum")
     */
    protected $status;

    /**
     * The push initiating user (if a user)
     *
     * @var null|User
     * @ManyToOne(targetEntity="User", inversedBy="pushes")
     * @JoinColumn(name="UserId", referencedColumnName="UserId", nullable=true)
     */
    protected $user;

    /**
     * The push initiating consumer (if a consumer)
     *
     * @var null|Consumer
     * @ManyToOne(targetEntity="Consumer")
     * @JoinColumn(name="ConsumerId", referencedColumnName="ConsumerId", nullable=true)
     */
    protected $consumer;

    /**
     * The push build
     *
     * @var Build
     * @ManyToOne(targetEntity="Build")
     * @JoinColumn(name="BuildId", referencedColumnName="BuildId")
     */
    protected $build;

    /**
     * The push deployment
     *
     * @var Deployment
     * @ManyToOne(targetEntity="Deployment", inversedBy="pushes")
     * @JoinColumn(name="DeploymentId", referencedColumnName="DeploymentId", nullable=true)
     */
    protected $deployment;

    /**
     * The push repository
     *
     * @var Repository
     * @ManyToOne(targetEntity="Repository")
     * @JoinColumn(name="RepositoryId", referencedColumnName="RepositoryId")
     */
    protected $repository;

    /**
     * The event logs for this job
     *
     * @var ArrayCollection
     * @OneToMany(targetEntity="EventLog", mappedBy="push")
     * @OrderBy({"event" = "ASC", "order" = "ASC"})
     */
    protected $logs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->created = null;
        $this->start = null;
        $this->end = null;
        $this->status = null;
        $this->user = null;
        $this->consumer = null;
        $this->build = null;
        $this->deployment = null;
        $this->repository = null;
        $this->logs = null;
    }

    /**
     * Set the push id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the push id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the push created time
     *
     * @param null|TimePoint $created
     */
    public function setCreated(TimePoint $created = null)
    {
        $this->created = $created;
    }

    /**
     * Get the push created time
     *
     * @return null|TimePoint
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set the push start time
     *
     * @param null|TimePoint $start
     */
    public function setStart(TimePoint $start = null)
    {
        $this->start = $start;
    }

    /**
     * Get the push start time
     *
     * @return null|TimePoint
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set the push end time
     *
     * @param null|TimePoint $end
     */
    public function setEnd(TimePoint $end = null)
    {
        $this->end = $end;
    }

    /**
     * Get the push end time
     *
     * @return null|TimePoint
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set the push status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get the push status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the push user
     *
     * @param null|User $user
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Get the push user
     *
     * @return null|User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the push consumer
     *
     * @param null|Consumer $consumer
     */
    public function setConsumer($consumer)
    {
        $this->consumer = $consumer;
    }

    /**
     * Get the push consumer
     *
     * @return null|Consumer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     * Set the push build
     *
     * @param Build $build
     */
    public function setBuild(Build $build)
    {
        $this->build = $build;
    }

    /**
     * Get the push build
     *
     * @return Build
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * Set the push deployment
     *
     * @param Deployment $deployment
     */
    public function setDeployment(Deployment $deployment)
    {
        $this->deployment = $deployment;
    }

    /**
     * Get the push deployment
     *
     * @return Deployment
     */
    public function getDeployment()
    {
        return $this->deployment;
    }

    /**
     * Set the push repository
     *
     * @param Repository $repository
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get the push repository
     *
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set the event logs
     *
     * @param ArrayCollection $logs
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
    }

    /**
     * Get the event logs
     *
     * @return ArrayCollection
     */
    public function getLogs()
    {
        return $this->logs;
    }
}
