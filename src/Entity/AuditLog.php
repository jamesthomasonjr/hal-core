<?php
# src/QL/Hal/Core/Entity/Log.php

namespace QL\Hal\Core\Entity;

use MCP\DataType\Time\TimePoint;

/**
 * Audit Log Entity
 *
 * @author Matt Colf <matthewcolf@quickenloans.com>
 * @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\AuditLogRepository")
 * @Table(name="AuditLogs")
 */
class AuditLog
{
    /**
     * The log id
     *
     * @var int
     * @Id @Column(name="AuditLogId", type="integer", unique=true)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The user that initiated the action
     *
     * @var User
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="UserId", referencedColumnName="UserId")
     */
    private $user;

    /**
     * When the log entry was recorded
     *
     * @var Timepoint
     * @Column(name="Recorded", type="timepoint")
     */
    private $recorded;

    /**
     * The entity type the action was taken on
     *
     * @var string
     * @Column(name="Entity", type="string", length=255)
     */
    private $entity;

    /**
     * The action that was taken
     *
     * @var string
     * @Column(name="Action", type="string", length=24)
     */
    private $action;

    /**
     * The data associated with this action
     *
     * @var string
     * @Column(name="Data", type="string")
     */
    private $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->user = null;
        $this->recorded = null;
        $this->entity = null;
        $this->action = null;
        $this->data = null;
    }

    /**
     * Set the action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get the action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set the data
     *
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Get the data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the entity type
     *
     * @param string $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Get the entity type
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set the id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the recorded Timepoint
     *
     * @param Timepoint $recorded
     */
    public function setRecorded(Timepoint $recorded)
    {
        $this->recorded = $recorded;
    }

    /**
     * Get the recorded Timepoint
     *
     * @return Timepoint
     */
    public function getRecorded()
    {
        return $this->recorded;
    }

    /**
     * Set the User
     *
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the User
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
