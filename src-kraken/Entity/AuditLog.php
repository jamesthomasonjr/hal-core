<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Kraken\Core\Entity;

use MCP\DataType\Time\TimePoint;
use QL\Hal\Core\Entity\User;

class AuditLog
{
    /**
     * @type string
     */
    protected $id;

    /**
     * @type Timepoint
     */
    protected $created;

    /**
     * @type User
     */
    protected $user;

    /**
     * @type string
     */
    protected $entity;
    protected $action;
    protected $data;

    public function __construct()
    {
        $this->id = '';
        $this->created = null;
        $this->user = null;

        $this->entity = '';
        $this->action = '';
        $this->data = '';
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return Timepoint
     */
    public function created()
    {
        return $this->created;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function entity()
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param string $id
     */
    public function withId($id)
    {
        $this->id = $id;
    }

    /**
     * @param Timepoint $created
     */
    public function withCreated(Timepoint $created)
    {
        $this->created = $created;
    }

    /**
     * @param User $user
     */
    public function withUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $action
     */
    public function withAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param string $entity
     */
    public function withEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param string $data
     */
    public function withData($data)
    {
        $this->data = $data;
    }
}
