<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

/**
 *  Environment Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\EnvironmentRepository")
 *  @Table(name="Environments")
 */
class Environment
{
    /**
     *  The environment id
     *
     *  @var integer
     *  @Id @Column(name="EnvironmentId", type="integer", unique=true)
     *  @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *  The environment key
     *
     *  @var string
     *  @Column(name="EnvironmentKey", type="string", length=24, unique=true)
     */
    protected $key;

    /**
     *  The environment display order
     *
     *  @var integer
     *  @Column(name="EnvironmentOrder", type="integer")
     */
    protected $order;

    /**
     *  Set the environment id
     *
     *  @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *  Get the environment id
     *
     *  @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Set the environment key
     *
     *  @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     *  Get the environment key
     *
     *  @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     *  Set the environment display order
     *
     *  @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     *  Get the environment display order
     *
     *  @return int
     */
    public function getOrder()
    {
        return $this->order;
    }


}
