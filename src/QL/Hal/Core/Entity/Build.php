<?php
# src/QL/Hal/Core/Entity/Build.php

namespace QL\Hal\Core\Entity;

use MCP\DataType\Time\TimePoint;

/**
 *  Build Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\BuildRepository")
 *  @Table(name="Builds")
 */
class Build
{
    /**
     *  The build id
     *
     *  @var string
     *  @Id @Column(name="BuildId", type="string", length=128, unique=true)
     */
    private $id;

    /**
     *  The build created time
     *
     *  @var null|TimePoint
     *  @Column(name="BuildCreated", type="timepoint", nullable=true)
     */
    private $created;

    /**
     *  The build start time
     *
     *  @var null|TimePoint
     *  @Column(name="BuildStart", type="timepoint", nullable=true)
     */
    private $start;

    /**
     *  The build end time
     *
     *  @var null|TimePoint
     *  @Column(name="BuildEnd", type="timepoint", nullable=true)
     */
    private $end;

    /**
     *  The build status
     *
     *  @var string
     *  @Column(name="BuildStatus", type="buildstatusenum")
     */
    private $status;

    /**
     *  The build branch name
     *
     *  @var string
     *  @Column(name="BuildBranch", type="string", length=64)
     */
    private $branch;

    /**
     *  The build commit hash
     *
     *  @var string
     *  @Column(name="BuildCommit", type="string", length=40)
     */
    private $commit;

    /**
     *  The build initiating user (if a user)
     *
     *  @var null|User
     *  @OneToOne(targetEntity="User")
     *  @JoinColumn(name="UserId", referencedColumnName="UserId", nullable=true)
     */
    private $user;

    /**
     *  The build initiating consumer(if a consumer)
     *
     *  @var null|Consumer
     *  @OneToOne(targetEntity="Consumer")
     *  @JoinColumn(name="ConsumerId", referencedColumnName="ConsumerId", nullable=true)
     */
    private $consumer;

    /**
     *  The build repository
     *
     *  @var Repository
     *  @OneToOne(targetEntity="Repository")
     *  @JoinColumn(name="RepositoryId", referencedColumnName="RepositoryId")
     */
    private $repository;

    /**
     *  The build environment
     *
     *  @var Environment
     *  @OneToOne(targetEntity="Environment")
     *  @JoinColumn(name="EnvironmentId", referencedColumnName="EnvironmentId")
     */
    private $environment;

    /**
     *  Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->created = null;
        $this->start = null;
        $this->end = null;
        $this->status = null;
        $this->branch = null;
        $this->commit = null;
        $this->user = null;
        $this->consumer = null;
        $this->repository = null;
        $this->environment = null;
    }

    /**
     *  Set the build id
     *
     *  @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *  Get the build id
     *
     *  @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Set the push created time
     *
     *  @param null|TimePoint $created
     */
    public function setCreated(TimePoint $created = null)
    {
        $this->created = $created;
    }

    /**
     *  Get the push created time
     *
     *  @return null|TimePoint
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     *  Set the build start time
     *
     *  @param TimePoint|null $start
     */
    public function setStart(TimePoint $start = null)
    {
        $this->start = $start;
    }

    /**
     *  Get the build start time
     *
     *  @return TimePoint|null
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     *  Set the build end time
     *
     * @param TimePoint|null $end
     */
    public function setEnd(TimePoint $end = null)
    {
        $this->end = $end;
    }

    /**
     *  Get the build end time
     *
     *  @return TimePoint|null
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     *  Set the build status
     *
     *  @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     *  Get the build status
     *
     *  @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *  Set the build branch name
     *
     *  @param string $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    /**
     *  Get the build branch name
     *
     *  @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     *  Set the build commit hash
     *
     *  @param string $commit
     */
    public function setCommit($commit)
    {
        $this->commit = $commit;
    }

    /**
     *  Get the build commit hash
     *
     *  @return string
     */
    public function getCommit()
    {
        return $this->commit;
    }

    /**
     *  Set the build user
     *
     *  @param null|User $user
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    }

    /**
     *  Get the build user
     *
     *  @return null|User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *  Set the build consumer
     *
     *  @param null|Consumer $consumer
     */
    public function setConsumer(Consumer $consumer = null)
    {
        $this->consumer = $consumer;
    }

    /**
     *  Get the build consumer
     *
     *  @return null|Consumer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     *  Set the build repository
     *
     *  @param Repository $repository
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *  Get the build repository
     *
     *  @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     *  Set the build environment
     *
     *  @param Environment $environment
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     *  Get the build environment
     *
     *  @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }
}
