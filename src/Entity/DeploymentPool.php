<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

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
     * @type int[]
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
        $this->deployments = [];
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
     * @return int[]
     */
    public function deployments()
    {
        return $this->deployments;
    }

    /**
     * @param Deployment|int $deployment
     *
     * @return bool
     */
    public function inPool($deployment)
    {
        if ($deployment instanceof Deployment) {
            $deployment = $deployment->id();
        }

        return in_array($deployment, $this->deployments, true);
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
     * @param Deployment|int $deployment
     *
     * @return self
     */
    public function withDeployment($deployment)
    {
        if ($deployment instanceof Deployment) {
            $deployment = $deployment->id();
        }

        if (is_int($deployment) && !in_array($deployment, $this->deployments, true)) {
            $this->deployments[] = $deployment;
        }

        return $this;
    }

    /**
     * @param Deployment[]|int[] $deployments
     *
     * @return self
     */
    public function withDeployments(array $deployments)
    {
        $this->deployments = [];

        foreach ($deployments as $deployment) {
            $this->withDeployment($deployment);
        }

        return $this;
    }

    /**
     * @param Deployment|int $deployment
     *
     * @return self
     */
    public function withoutDeployment($deployment)
    {
        if ($deployment instanceof Deployment) {
            $deployment = $deployment->id();
        }

        $saved = array_fill_keys($this->deployments, true);

        if (array_key_exists($deployment, $saved)) {
            unset($saved[$deployment]);
        }

        $this->deployments = array_keys($saved);

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
            'deployments' => $this->deployments(),
        ];

        return $json;
    }
}
