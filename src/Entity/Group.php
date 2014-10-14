<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 *  Group Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\GroupRepository")
 *  @Table(name="Groups")
 */
class Group
{
    /**
     *  The group id
     *
     *  @var integer
     *  @Id @Column(name="GroupId", type="integer", unique=true)
     *  @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *  The group key
     *
     *  @var string
     *  @Column(name="GroupKey", type="string", length=24, unique=true)
     */
    private $key;

    /**
     *  The group name
     *
     *  @var string
     *  @Column(name="GroupName", type="string", length=48)
     */
    private $name;

    /**
     *  The group repositories
     *
     *  @var ArrayCollection
     *  @OneToMany(targetEntity="Repository", mappedBy="group")
     */
    private $repositories;

    /**
     *  Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->key = null;
        $this->name = null;
        $this->repositories = new ArrayCollection();
    }

    /**
     *  Set the group id
     *
     *  @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *  Get the group id
     *
     *  @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Set the group key
     *
     *  @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     *  Get the group key
     *
     *  @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     *  Set the group name
     *
     *  @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *  Get the group name
     *
     *  @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *  Set the group repositories
     *
     *  @param ArrayCollection $repositories
     */
    public function setRepositories($repositories)
    {
        $this->repositories = $repositories;
    }

    /**
     *  Get the group repositories
     *
     *  @return ArrayCollection
     */
    public function getRepositories()
    {
        return $this->repositories;
    }
}
