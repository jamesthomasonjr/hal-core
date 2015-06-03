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
     * @type string
     */
    protected $entity;
    protected $action;
    protected $data;

    /**
     * @type User
     */
    protected $user;

    /**
     * @type Application
     */
    protected $application;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->created = null;

        $this->entity = '';
        $this->action = '';
        $this->data = '';

        $this->user = null;
        $this->application = null;
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
     * @return string
     */
    public function entity()
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->key;
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
    public function data()
    {
        return $this->data;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param Timepoint $created
     *
     * @return self
     */
    public function withCreated(Timepoint $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @param string $entity
     *
     * @return self
     */
    public function withEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param string $key
     *
     * @return self
     */
    public function withKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $action
     *
     * @return self
     */
    public function withAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @param string $data
     *
     * @return self
     */
    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param User $user
     *
     * @return self
     */
    public function withUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Application $application
     *
     * @return self
     */
    public function withApplication(Application $application)
    {
        $this->application = $application;
        return $this;
    }
}
