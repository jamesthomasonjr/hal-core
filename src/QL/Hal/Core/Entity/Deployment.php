<?php
# src/QL/Hal/Core/Entity/Deployment.php

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 *  Deployment Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\DeploymentRepository")
 *  @Table(name="Deployments")
 */
class Deployment
{
    /**
     *  The deployment id
     *
     *  @var int
     *  @Id @Column(name="DeploymentId", type="integer", unique=true)
     *  @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *  The deployment path
     *
     *  @var string
     *  @Column(name="DeploymentPath", type="string", length=255)
     */
    private $path;

    /**
     *  @var Repository
     *  @ManyToOne(targetEntity="Repository", inversedBy="deployments")
     *  @JoinColumn(name="RepositoryId", referencedColumnName="RepositoryId")
     */
    private $repository;

    /**
     *  @var Server
     *  @ManyToOne(targetEntity="Server", inversedBy="deployments")
     *  @JoinColumn(name="ServerId", referencedColumnName="ServerId")
     */
    private $server;

    /**
     *  @var ArrayCollection
     *  @OneToMany(targetEntity="Push", mappedBy="deployment")
     *  @OrderBy({"start" = "DESC"})
     */
    private $pushes;

    /**
     *  Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->path = null;
        $this->repository = null;
        $this->server = null;
        $this->pushes = new ArrayCollection();
    }

    /**
     *  Set the deployment id
     *
     *  @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *  Get the deployment id
     *
     *  @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Set the deployment path
     *
     *  @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     *  Get the deployment path
     *
     *  @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     *  Set the deployment repository
     *
     *  @param Repository $repository
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *  Get the deployment repository
     *
     *  @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     *  Set the deployment server
     *
     *  @param Server $server
     */
    public function setServer(Server $server)
    {
        $this->server = $server;
    }

    /**
     *  Get the deployment server
     *
     *  @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

}
