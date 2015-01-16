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
     * For RSYNC
     *
     * The path
     *
     * @var string
     *
     * @Column(name="DeploymentPath", type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * For ELASTIC BEANSTALK
     *
     * The EB environment ID
     *
     * @var string
     *
     * @Column(name="DeploymentEbEnvironment", type="string", length=100, nullable=true)
     */
    protected $ebEnvironment;

    /**
     * For EC2
     *
     * The EC2 autoscaling pool. EC2 Instances must be tagged with the pool they belong to. This is how HAL knows where to put code.
     *
     * @var string
     *
     * @Column(name="DeploymentEc2Pool", type="string", length=100, nullable=true)
     */
    protected $ec2Pool;

    /**
     * @var HttpUrl|null
     *
     * @Column(name="DeploymentUrl", type="url")
     */
    protected $url;

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
        $this->ebEnvironment = null;
        $this->ec2Pool = null;

        $this->repository = null;
        $this->server = null;
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
     * Set the rsync path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get the rsync path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the EB environment ID
     *
     * @param string $ebEnvironment
     */
    public function setEbEnvironment($ebEnvironment)
    {
        $this->ebEnvironment = $ebEnvironment;
    }

    /**
     * Get the EB environment ID
     *
     * @return string
     */
    public function getEbEnvironment()
    {
        return $this->ebEnvironment;
    }

    /**
     * Set the EC2 Pool
     *
     * @param string $ec2Pool
     */
    public function setEc2Pool($ec2Pool)
    {
        $this->ec2Pool = $ec2Pool;
    }

    /**
     * Get the EC2 Pool
     *
     * @return string
     */
    public function getEc2Pool()
    {
        return $this->ec2Pool;
    }

    /**
     * Set the url
     *
     * @param HttpUrl $url
     */
    public function setUrl(HttpUrl $url)
    {
        $this->url = $url;
    }

    /**
     * Get the url
     *
     * @return HttpUrl|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the repository
     *
     * @param Repository $repository
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get the repository
     *
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set the server
     *
     * @param Server $server
     */
    public function setServer(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Get the server
     *
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set the pushes
     *
     * @param ArrayCollection $pushes
     */
    public function setPushes($pushes)
    {
        $this->pushes = $pushes;
    }

    /**
     * Get the pushes
     *
     * @return ArrayCollection
     */
    public function getPushes()
    {
        return $this->pushes;
    }
}
