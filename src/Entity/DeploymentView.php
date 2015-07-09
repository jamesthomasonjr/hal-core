<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

class DeploymentView implements JsonSerializable
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
     * Application is optional to possibly support global views.
     *
     * @type Application|null
     */
    protected $application;

    /**
     * @type Environment
     */
    protected $environment;

    /**
     * Lack of an owner signifies this view can be edited by all.
     *
     * @type User|null
     */
    protected $user;

    /**
     * @type ArrayCollection
     */
    protected $pools;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->name = '';

        $this->application = null;
        $this->environment = null;
        $this->user = null;

        $this->pools = new ArrayCollection;
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
     * @return Application|null
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return Environment
     */
    public function environment()
    {
        return $this->environment;
    }

    /**
     * @return User|null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return ArrayCollection
     */
    public function pools()
    {
        return $this->pools;
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
     * @param Application|null $application
     *
     * @return self
     */
    public function withApplication(Application $application = null)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @param Environment $environment
     *
     * @return self
     */
    public function withEnvironment(Environment $environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @param User|null $user
     *
     * @return self
     */
    public function withUser(User $user = null)
    {
        $this->user = $user;
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

            'application' => $this->application() ? $this->application()->id() : null,
            'environment' => $this->environment() ? $this->environment()->id() : null,
            'user' => $this->user() ? $this->user()->id() : null,
        ];

        return $json;
    }
}
