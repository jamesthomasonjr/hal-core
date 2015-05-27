<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
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
     * @type Repository
     */
    protected $application;

    public function __construct()
    {
        $this->id = '';
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
     * @return Repository
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
     * @param Repository $application
     *
     * @return self
     */
    public function withApplication(Repository $application)
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

            'user' => $this->user()->getId(),
            'application' => $this->application()->getId()
        ];

        return $json;
    }
}
