<?php
# src/QL/Hal/Core/Entity/Push.php

namespace QL\Hal\Core\Entity;

use MCP\DataType\Time\TimePoint;

/**
 *  Push Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\PushRepository")
 *  @Table(name="Pushes")
 */
class Push
{
    /**
     *  The push id
     *
     *  @var int
     *  @Id @Column(name="PushId", type="integer", unique=true)
     *  @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *  The push start time
     *
     *  @var TimePoint|null;
     *  @Column(name="PushStart", type="timepoint", nullable=true)
     */
    private $start;

    /**
     *  The push end time
     *
     *  @var TimePoint;
     *  @Column(name="PushEnd", type="timepoint", nullable=true)
     */
    private $end;

    /**
     *  The push status
     *
     *  @var string
     *  @Column(name="PushStatus", type="pushstatusenum")
     */
    private $status;

    /**
     *  The push initiating user (if a user)
     *
     *  @var null|User
     *  @OneToOne(targetEntity="User")
     *  @JoinColumn(name="UserId", referencedColumnName="UserId", nullable=true)
     */
    private $user;

    /**
     *  The push initiating consumer (if a consumer)
     *
     *  @var null|Consumer
     *  @OneToOne(targetEntity="Consumer")
     *  @JoinColumn(name="ConsumerId", referencedColumnName="ConsumerId", nullable=true)
     */
    private $consumer;

    /**
     *  The push build
     *
     *  @var Build
     *  @OneToOne(targetEntity="Build")
     *  @JoinColumn(name="BuildId", referencedColumnName="BuildId")
     */
    private $build;

    /**
     *  The push deployment
     *
     *  @var Deployment
     *  @OneToOne(targetEntity="Deployment")
     *  @JoinColumn(name="DeploymentId", referencedColumnName="DeploymentId")
     */
    private $deployment;

    /**
     *  Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->start = null;
        $this->end = null;
        $this->status = null;
        $this->user = null;
        $this->consumer = null;
        $this->build = null;
        $this->deployment = null;
    }

    /**
     *  Set the push id
     *
     *  @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *  Get the push id
     *
     *  @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Set the push start time
     *
     *  @param null|TimePoint $start
     */
    public function setStart(TimePoint $start = null)
    {
        $this->start = $start;
    }

    /**
     *  Get the push start time
     *
     *  @return null|TimePoint
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     *  Set the push end time
     *
     * @param null|TimePoint $end
     */
    public function setEnd(TimePoint $end = null)
    {
        $this->end = $end;
    }

    /**
     *  Get the push end time
     *
     *  @return null|TimePoint
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     *  Set the push status
     *
     *  @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     *  Get the push status
     *
     *  @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *  Set the push user
     *
     *  @param null|User $user
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    }

    /**
     *  Get the push user
     *
     *  @return null|User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *  Set the push consumer
     *
     *  @param null|Consumer $consumer
     */
    public function setConsumer($consumer)
    {
        $this->consumer = $consumer;
    }

    /**
     *  Get the push consumer
     *
     *  @return null|Consumer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     *  Set the push build
     *
     *  @param Build $build
     */
    public function setBuild(Build $build)
    {
        $this->build = $build;
    }

    /**
     *  Get the push build
     *
     *  @return Build
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     *  Set the push deployment
     *
     *  @param Deployment $deployment
     */
    public function setDeployment(Deployment $deployment)
    {
        $this->deployment = $deployment;
    }

    /**
     *  Get the push deployment
     *
     *  @return Deployment
     */
    public function getDeployment()
    {
        return $this->deployment;
    }


}
