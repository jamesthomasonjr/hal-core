<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

class DeploymentPool implements JsonSerializable
{
    /**
     * @type string
     */
    protected $id;

    /**
     * @type string
     */
    protected $name;

    /**
     * @type DeploymentView
     */
    protected $view;

    /**
     * @type ArrayCollection
     */
    protected $deployments;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->name = '';

        $this->view = null;
        $this->deployments = new ArrayCollection;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return DeploymentView
     */
    public function view()
    {
        return $this->view;
    }

    /**
     * @return ArrayCollection
     */
    public function deployments()
    {
        return $this->deployments;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $key
     *
     * @return self
     */
    public function withName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param DeploymentView $view
     *
     * @return self
     */
    public function withView(DeploymentView $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),
            'name' => $this->name(),

            'view' => $this->view() ? $this->view()->id() : null,
            // 'deployments' => $this->deployments(),
        ];

        return $json;
    }
}
