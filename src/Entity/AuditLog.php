<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use MCP\DataType\Time\TimePoint;

class AuditLog
{
    /**
     * @type int
     */
    protected $id;

    /**
     * @type User
     */
    protected $user;

    /**
     * @type Timepoint
     */
    protected $created;

    /**
     * @type string
     */
    protected $entity;

    /**
     * @type string
     */
    protected $action;

    /**
     * @type string
     */
    protected $data;

    public function __construct()
    {
        $this->id = null;
        $this->user = null;
        $this->created = null;

        $this->entity = '';
        $this->action = '';
        $this->data = '';
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
     * @param Timepoint $created
     */
    public function setCreated(Timepoint $created)
    {
        $this->created = $created;
    }

    /**
     * @return Timepoint
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
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
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
