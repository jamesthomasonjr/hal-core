<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use MCP\DataType\Time\TimePoint;

/**
 *  Session Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\SessionRepository")
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
     *  @var TimePoint
     *  @Column(name="SessionLastAccess", type="timepoint")
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
     *  @param TimePoint $lastAccess
     */
    public function setLastAccess(TimePoint $lastAccess)
    {
        $this->lastAccess = $lastAccess;
    }

    /**
     *  Get the last access time
     *
     *  @return TimePoint
     */
    public function getLastAccess()
    {
        return $this->lastAccess;
    }


}
