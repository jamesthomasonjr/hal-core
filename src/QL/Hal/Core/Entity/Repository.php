<?php
# src/QL/Hal/Core/Entity/Repository.php

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 *  Repository Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity
 *  @Table(name="Repositories")
 */
class Repository
{
    /**
     *  The repository id
     *
     *  @var int
     *  @Id @Column(name="RepositoryId", type="integer", unique=true)
     *  @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *  The repository key
     *
     *  @var string
     *  @Column(name="RepositoryKey", type="string", length=24, unique=true)
     */
    private $key;

    /**
     *  The repository description
     *
     *  @var string
     *  @Column(name="RepositoryDescription", type="string", length="255")
     */
    private $description;

    /**
     *  The repository Github user
     *
     *  @var string
     *  @Column(name="RepositoryGithubUser", type="string", length=48)
     */
    private $githubUser;

    /**
     *  The repository Github repository
     *
     *  @var string
     *  @Column(name="RepositoryGithubRepo", type="string", length=48)
     */
    private $githubRepo;

    /**
     *  The repository email address
     *
     *  @var string
     *  @Column(name="RepositoryEmail", type="string", length=128)
     */
    private $email;

    /**
     *  The repository build command
     *
     *  @var string
     *  @Column(name="RepositoryBuildCmd", type="string", length=128)
     */
    private $buildCmd;

    /**
     *  The repository pre push command
     *
     *  @var string
     *  @Column(name="RepositoryPrePushCmd", type="string", length=128)
     */
    private $prePushCmd;

    /**
     *  The repository post push command
     *
     *  @var string
     *  @Column(name="RepositoryPostPushCommand", type="string", length=128)
     */
    private $postPushCmd;

    /**
     *  The repository group
     *
     *  @var Group
     *  @ManyToOne(targetEntity="Group", inversedBy="repositories")
     *  @JoinColumn(name="GroupId", referencedColumnName="GroupId")
     */
    private $group;

    /**
     *  The repository deployments
     *
     *  @var ArrayCollection
     *  @OneToMany(targetEntity="Deployment", mappedBy="repository")
     */
    private $deployments;

    /**
     *  Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->key = null;
        $this->description = null;
        $this->githubUser = null;
        $this->githubRepo = null;
        $this->email = null;
        $this->buildCmd = null;
        $this->prePushCmd = null;
        $this->postPushCmd = null;
        $this->group = null;
        $this->deployments = new ArrayCollection();
    }

    /**
     *  Set the repository build command
     *
     *  @param string $buildCmd
     */
    public function setBuildCmd($buildCmd)
    {
        $this->buildCmd = $buildCmd;
    }

    /**
     *  Get the repository build command
     *
     *  @return string
     */
    public function getBuildCmd()
    {
        return $this->buildCmd;
    }

    /**
     *  Set the repository description
     *
     *  @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     *  Get the repository description
     *
     *  @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *  Set the repository email address
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     *  Get the repository email address
     *
     *  @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *  Set the repository Github Repository
     *
     *  @param string $githubRepo
     */
    public function setGithubRepo($githubRepo)
    {
        $this->githubRepo = $githubRepo;
    }

    /**
     *  Get the repository Github repository
     *
     *  @return string
     */
    public function getGithubRepo()
    {
        return $this->githubRepo;
    }

    /**
     *  Set the repository Github user
     *
     *  @param string $githubUser
     */
    public function setGithubUser($githubUser)
    {
        $this->githubUser = $githubUser;
    }

    /**
     *  Get the repository Github user
     *
     *  @return string
     */
    public function getGithubUser()
    {
        return $this->githubUser;
    }

    /**
     *  Set the repository group
     *
     *  @param \QL\Hal\Core\Entity\Group $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     *  Get the repository group
     *
     *  @return \QL\Hal\Core\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     *  Set the repository id
     *
     *  @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *  Get the repository id
     *
     *  @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Set the repository key
     *
     *  @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     *  Get the repository key
     *
     *  @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     *  Set the repository post push command
     *
     *  @param string $postPushCmd
     */
    public function setPostPushCmd($postPushCmd)
    {
        $this->postPushCmd = $postPushCmd;
    }

    /**
     *  Get the repository post push command
     *
     *  @return string
     */
    public function getPostPushCmd()
    {
        return $this->postPushCmd;
    }

    /**
     *  Set the repository pre push command
     *
     * @param string $prePushCmd
     */
    public function setPrePushCmd($prePushCmd)
    {
        $this->prePushCmd = $prePushCmd;
    }

    /**
     *  Get the repository pre push command
     *
     *  @return string
     */
    public function getPrePushCmd()
    {
        return $this->prePushCmd;
    }

}
