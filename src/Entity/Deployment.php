<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use MCP\DataType\HttpUrl;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\DeploymentRepository")
 * @Table(name="Deployments")
 */
class Deployment
{
    /**
     * The deployment id
     *
     * @var int
     *
     * @Id @Column(name="DeploymentId", type="integer", unique=true)
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The deployment type
     *
     * @var string
     *
     * @Column(name="DeploymentType", type="deploymentenum")
     */
    protected $type;

    /**
     * For RSYNC
     *
     * The deployment path
     *
     * @var string
     *
     * @Column(name="DeploymentPath", type="string", length=255)
     */
    protected $path;

    /**
     * For RSYNC
     *
     * @var HttpUrl|null
     *
     * @Column(name="DeploymentUrl", type="url")
     */
    protected $url;

    /**
     * For ELASTIC BEANSTALK
     *
     * The deployment environment id
     *
     * @var string
     *
     * @Column(name="DeploymentEbsEnvironment", type="string", length=100)
     */
    protected $ebsEnvironment;

    /**
     * @var Repository
     *
     * @ManyToOne(targetEntity="Repository", inversedBy="deployments")
     * @JoinColumn(name="RepositoryId", referencedColumnName="RepositoryId")
     */
    protected $repository;

    /**
     * For RSYNC
     *
     * @var Server|null
     *
     * @ManyToOne(targetEntity="Server", inversedBy="deployments")
     * @JoinColumn(name="ServerId", referencedColumnName="ServerId")
     */
    protected $server;

    /**
     * @var ArrayCollection
     *
     * @OneToMany(targetEntity="Push", mappedBy="deployment")
     * @OrderBy({"created" = "DESC"})
     */
    protected $pushes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->path = null;
        $this->repository = null;
        $this->server = null;
        $this->type = null;
        $this->pushes = new ArrayCollection();
    }

    /**
     * Set the deployment id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the deployment id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the deployment type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get the deployment type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the deployment path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get the deployment path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the deployment url
     *
     * @param HttpUrl $url
     */
    public function setUrl(HttpUrl $url)
    {
        $this->url = $url;
    }

    /**
     * Get the deployment url
     *
     * @return HttpUrl|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the deployment repository
     *
     * @param Repository $repository
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get the deployment repository
     *
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set the deployment server
     *
     * @param Server $server
     */
    public function setServer(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Get the deployment server
     *
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set the EBS Environment ID
     *
     * @param string $ebsEnvironment
     */
    public function setEbsEnvironment($ebsEnvironment)
    {
        $this->ebsEnvironment = $ebsEnvironment;
    }

    /**
     * Get the EBS Environment ID
     *
     * @return string|null
     */
    public function getEbsEnvironment()
    {
        return $this->ebsEnvironment;
    }

    /**
     * Set the deployment pushes
     *
     * @param ArrayCollection $pushes
     */
    public function setPushes($pushes)
    {
        $this->pushes = $pushes;
    }

    /**
     * Get the deployment pushes
     *
     * @return ArrayCollection
     */
    public function getPushes()
    {
        return $this->pushes;
    }
}
