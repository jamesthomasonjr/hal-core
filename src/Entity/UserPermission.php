<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use JsonSerializable;

class UserPermission implements JsonSerializable
{
    /**
     * @type string
     */
    protected $id;

    /**
     * @type bool
     */
    protected $isProduction;

    /**
     * @type User
     */
    protected $user;

    /**
     * @type Application
     */
    protected $application;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->isProduction = false;

        $this->user = null;
        $this->application = null;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isProduction()
    {
        return $this->isProduction;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return Application
     */
    public function application()
    {
        return $this->application;
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
     * @param bool $isProduction
     *
     * @return self
     */
    public function withIsProduction($isProduction)
    {
        $this->isProduction = $isProduction;
        return $this;
    }

    /**
     * @param User $user
     *
     * @return self
     */
    public function withUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Application $application
     *
     * @return self
     */
    public function withApplication(Application $application)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),
            'isProduction' => $this->isProduction(),

            'user' => $this->user() ? $this->user()->id() : null,
            'application' => $this->application() ? $this->application()->id() : null
        ];

        return $json;
    }
}
