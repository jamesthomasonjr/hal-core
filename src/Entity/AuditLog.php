<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use QL\MCP\Common\Time\TimePoint;

class AuditLog
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var TimePoint
     */
    protected $created;

    /**
     * @var string
     */
    protected $entity;
    protected $action;
    protected $data;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->user = null;
        $this->created = null;

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
     * @return TimePoint
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
     * @param int $id
     *
     * @return self
     */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param TimePoint $created
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
}
