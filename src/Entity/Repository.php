<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\RepositoryRepository")
 * @Table(name="Repositories")
 */
class Repository
{
    /**
     * The repository id
     *
     * @var int
     *
     * @Id @Column(name="RepositoryId", type="integer", unique=true)
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The repository key
     *
     * @var string
     *
     * @Column(name="RepositoryKey", type="string", length=24, unique=true)
     */
    protected $key;

    /**
     * The repository description
     *
     * @var string
     *
     * @Column(name="RepositoryDescription", type="string", length=255)
     */
    protected $description;

    /**
     * The repository Github user
     *
     * @var string
     *
     * @Column(name="RepositoryGithubUser", type="string", length=48)
     */
    protected $githubUser;

    /**
     * The repository Github repository
     *
     * @var string
     *
     * @Column(name="RepositoryGithubRepo", type="string", length=48)
     */
    protected $githubRepo;

    /**
     * The repository email address
     *
     * @var string
     *
     * @Column(name="RepositoryEmail", type="string", length=128)
     */
    protected $email;

    /**
     * The repository build command
     *
     * @var null|string
     *
     * @Column(name="RepositoryBuildCmd", type="string", length=255)
     */
    protected $buildCmd;

    /**
     * The repository build transform command to be run before push
     *
     * @var string
     *
     * @Column(name="RepositoryBuildTransformCmd", type="string", length=255)
     */
    protected $buildTransformCmd;

    /**
     * The repository pre push command
     *
     * @var string
     *
     * @Column(name="RepositoryPrePushCmd", type="string", length=128)
     */
    protected $prePushCmd;

    /**
     * The repository post push command
     *
     * @var string
     *
     * @Column(name="RepositoryPostPushCmd", type="string", length=128)
     */
    protected $postPushCmd;

    /**
     * The application name for elastic beanstalk
     *
     * @var string
     *
     * @Column(name="RepositoryEbName", type="string", length=255)
     */
    protected $ebName;

    /**
     * The repository group
     *
     * @var Group
     *
     * @ManyToOne(targetEntity="Group", inversedBy="repositories")
     * @JoinColumn(name="GroupId", referencedColumnName="GroupId")
     */
    protected $group;

    /**
     * The repository deployments
     *
     * @var ArrayCollection
     *
     * @OneToMany(targetEntity="Deployment", mappedBy="repository")
     */
    protected $deployments;

    /**
     * Constructor
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
        $this->buildTransformCmd = null;
        $this->prePushCmd = null;
        $this->postPushCmd = null;

        $this->ebName = null;

        $this->group = null;
        $this->deployments = new ArrayCollection();
    }

    /**
     * Set the repository build command
     *
     * @param string $buildCmd
     */
    public function setBuildCmd($buildCmd)
    {
        $this->buildCmd = $buildCmd;
    }

    /**
     * Get the repository build command
     *
     * @return string
     */
    public function getBuildCmd()
    {
        return $this->buildCmd;
    }

    /**
     * Set the repository build transform command
     *
     * @param string $buildTransformCmd
     */
    public function setBuildTransformCmd($buildTransformCmd)
    {
        $this->buildTransformCmd = $buildTransformCmd;
    }

    /**
     * Get the repository build transform command
     *
     * @return string
     */
    public function getBuildTransformCmd()
    {
        return $this->buildTransformCmd;
    }

    /**
     * Set the repository description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get the repository description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the repository email address
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get the repository email address
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the repository Github Repository
     *
     * @param string $githubRepo
     */
    public function setGithubRepo($githubRepo)
    {
        $this->githubRepo = $githubRepo;
    }

    /**
     * Get the repository Github repository
     *
     * @return string
     */
    public function getGithubRepo()
    {
        return $this->githubRepo;
    }

    /**
     * Set the repository Github user
     *
     * @param string $githubUser
     */
    public function setGithubUser($githubUser)
    {
        $this->githubUser = $githubUser;
    }

    /**
     * Get the repository Github user
     *
     * @return string
     */
    public function getGithubUser()
    {
        return $this->githubUser;
    }

    /**
     * Set the repository group
     *
     * @param Group $group
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get the repository group
     *
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set the repository id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the repository id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the repository key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get the repository key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the repository post push command
     *
     * @param string $postPushCmd
     */
    public function setPostPushCmd($postPushCmd)
    {
        $this->postPushCmd = $postPushCmd;
    }

    /**
     * Get the repository post push command
     *
     * @return string
     */
    public function getPostPushCmd()
    {
        return $this->postPushCmd;
    }

    /**
     * Set the repository pre push command
     *
     * @param string $prePushCmd
     */
    public function setPrePushCmd($prePushCmd)
    {
        $this->prePushCmd = $prePushCmd;
    }

    /**
     * Get the repository pre push command
     *
     * @return string
     */
    public function getPrePushCmd()
    {
        return $this->prePushCmd;
    }

    /**
     * Set the application name for elastic beanstalk
     *
     * @param string $ebName
     */
    public function setEbName($ebName)
    {
        $this->ebName = $ebName;
    }

    /**
     * Get the application name for elastic beanstalk
     *
     * @return string
     */
    public function getEbName()
    {
        return $this->ebName;
    }
}
