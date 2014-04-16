<?php
# src/QL/Hal/Core/Entity/Session.php

namespace QL\Hal\Core\Entity;

use Datetime;

/**
 *  Session Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity
 *  @Table(name="Sessions")
 */
class Session
{
    /**
     *  The session id
     *
     *  @var string
     *  @Id @Column(name="SessionId", type="string", length=255, unique=true)
     */
    private $id;

    /**
     *  The session data
     *
     *  @var string
     *  @Column(name="SessionData", type="string")
     */
    private $data;

    /**
     *  The session last access time
     *
     *  @var Datetime
     *  @Column(name="SessionLastAccess", type="datetime")
     */
    private $lastAccess;

    /**
     *  Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->data = null;
        $this->lastAccess = null;
    }

    /**
     *  Set the session id
     *
     *  @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *  Get the session id
     *
     *  @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Set the session data
     *
     *  @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     *  Get the session data
     *
     *  @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *  Set the last access time
     *
     *  @param \Datetime $lastAccess
     */
    public function setLastAccess($lastAccess)
    {
        $this->lastAccess = $lastAccess;
    }

    /**
     *  Get the last access time
     *
     *  @return \Datetime
     */
    public function getLastAccess()
    {
        return $this->lastAccess;
    }


}
