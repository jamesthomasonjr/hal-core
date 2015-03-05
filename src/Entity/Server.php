<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Server
{
    /**
     * The server id
     *
     * @var int
     */
    protected $id;

    /**
     * The server type
     *
     * @var string
     */
    protected $type;

    /**
     * The server name (hostname)
     *
     * @var string
     */
    protected $name;

    /**
     * The environment
     *
     * @var Environment
     */
    protected $environment;

    /**
     * Deployments for the server
     *
     * @var ArrayCollection
     */
    protected $deployments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->type = null;
        $this->name = null;
        $this->environment = null;
        $this->deployments = new ArrayCollection();
    }

    /**
     * Set the server id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the server id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the server type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get the server type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the server name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the server name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the server environment
     *
     * @param Environment $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Get the server environment
     *
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Set the server deployments
     *
     * @param ArrayCollection $deployments
     */
    public function setDeployments(ArrayCollection $deployments)
    {
        $this->deployments = $deployments;
    }

    /**
     * Get the server deployments
     *
     * @return ArrayCollection
     */
    public function getDeployments()
    {
        return $this->deployments;
    }
}
