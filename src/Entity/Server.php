<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

class Server implements JsonSerializable
{
    /**
     * @type int
     */
    protected $id;

    /**
     * @type string
     */
    protected $type;

    /**
     * @type string
     */
    protected $name;

    /**
     * @type Environment
     */
    protected $environment;

    /**
     * Deployments for the server
     *
     * @type ArrayCollection
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
        $this->deployments = new ArrayCollection;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Environment $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param ArrayCollection $deployments
     */
    public function setDeployments(ArrayCollection $deployments)
    {
        $this->deployments = $deployments;
    }

    /**
     * @return ArrayCollection
     */
    public function getDeployments()
    {
        return $this->deployments;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->getId(),

            'type' => $this->getType(),
            'name' => $this->getName(),

            'environment' => $this->getEnvironment() ? $this->getEnvironment()->id() : null,

            // 'deployments' => $this->getDeployments() ? $this->getDeployments()->getKeys() : []
        ];

        return $json;
    }
}
