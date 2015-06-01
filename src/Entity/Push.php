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

class Push implements JsonSerializable
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
     * @type User
     */
    protected $user;

    /**
     * @type Build
     */
    protected $build;

    /**
     * @type Deployment
     */
    protected $deployment;

    /**
     * @type Repository
     */
    protected $repository;

    /**
     * @type ArrayCollection
     */
    protected $logs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = '';
        $this->created = null;
        $this->start = null;
        $this->end = null;
        $this->status = '';

        $this->user = null;
        $this->build = null;
        $this->deployment = null;
        $this->repository = null;
        $this->logs = new ArrayCollection;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
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
     * @param User $user
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Build $build
     */
    public function setBuild(Build $build)
    {
        $this->build = $build;
    }

    /**
     * @return Build
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * @param Deployment $deployment
     */
    public function setDeployment(Deployment $deployment)
    {
        $this->deployment = $deployment;
    }

    /**
     * @return Deployment
     */
    public function getDeployment()
    {
        return $this->deployment;
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

            'user' => $this->getUser() ? $this->getUser()->id() : null,
            'build' => $this->getBuild() ? $this->getBuild()->getId() : null,
            'deployment' => $this->getDeployment() ? $this->getDeployment()->getId() : null,
            'repository' => $this->getRepository() ? $this->getRepository()->getId() : null,

            // 'logs' => $this->getLogs() ? $this->getLogs()->getKeys() : []
        ];

        return $json;
    }
}
