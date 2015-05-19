<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use MCP\DataType\Time\TimePoint;

class Build implements JsonSerializable
{
    /**
     * @type string
     */
    protected $id;

    /**
     * @type TimePoint|null
     */
    protected $created;

    /**
     * @type TimePoint|null
     */
    protected $start;

    /**
     * @type TimePoint|null
     */
    protected $end;

    /**
     * @type string
     */
    protected $status;

    /**
     * @type string
     */
    protected $branch;

    /**
     * @type string
     */
    protected $commit;

    /**
     * @type null|User
     */
    protected $user;

    /**
     * @type Repository
     */
    protected $repository;

    /**
     * @type Environment
     */
    protected $environment;

    /**
     * @type ArrayCollection
     */
    protected $logs;

    public function __construct()
    {
        $this->id = null;

        $this->created = null;
        $this->start = null;
        $this->end = null;

        $this->status = null;
        $this->branch = '';
        $this->commit = '';

        $this->user = null;
        $this->repository = null;
        $this->environment = null;

        $this->logs = new ArrayCollection;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param TimePoint|null $created
     */
    public function setCreated(TimePoint $created = null)
    {
        $this->created = $created;
    }

    /**
     * @return TimePoint|null
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param TimePoint|null $start
     */
    public function setStart(TimePoint $start = null)
    {
        $this->start = $start;
    }

    /**
     * @return TimePoint|null
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param TimePoint|null $end
     */
    public function setEnd(TimePoint $end = null)
    {
        $this->end = $end;
    }

    /**
     * @return TimePoint|null
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    /**
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param string $commit
     */
    public function setCommit($commit)
    {
        $this->commit = $commit;
    }

    /**
     * @return string
     */
    public function getCommit()
    {
        return $this->commit;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Repository $repository
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param ArrayCollection $logs
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
    }

    /**
     * @return ArrayCollection
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->getId(),

            'created' => $this->getCreated() ? $this->getCreated()->format(DateTime::RFC3339, 'UTC') : null,
            'start' => $this->getStart() ? $this->getStart()->format(DateTime::RFC3339, 'UTC') : null,
            'end' => $this->getEnd() ? $this->getEnd()->format(DateTime::RFC3339, 'UTC') : null,

            'status' => $this->getStatus(),
            'branch' => $this->getBranch(),
            'commit' => $this->getCommit(),

            'user' => $this->getUser() ? $this->getUser()->getId() : null,
            'repository' => $this->getRepository() ? $this->getRepository()->getId() : null,
            'environment' => $this->getEnvironment() ? $this->getEnvironment()->getId() : null,

            // 'logs' => $this->getLogs() ? $this->getLogs()->getKeys() : []
        ];

        return $json;
    }
}
