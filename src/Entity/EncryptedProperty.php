<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use JsonSerializable;

class EncryptedProperty implements JsonSerializable
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
     * @type array
     */
    protected $data;

    /**
     * @type Repository
     */
    protected $repository;

    /**
     * The environment the encrypted property is for (NULL for all)
     *
     * @type Environment|null
     */
    protected $environment;

    public function __construct()
    {
        $this->id = null;
        $this->name = '';
        $this->data = '';

        $this->repository = null;
        $this->environment = null;
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
     * @return string
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Repository
     */
    public function repository()
    {
        return $this->repository;
    }

    /**
     * @return Environment|null
     */
    public function environment()
    {
        return $this->environment;
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
     * @param string $data
     *
     * @return self
     */
    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param Repository $repository
     *
     * @return self
     */
    public function withRepository(Repository $repository)
    {
        $this->repository = $repository;
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
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'name' => $this->name(),
            // 'data' => $this->getData(),
            'data' => '**ENCRYPTED**',

            'repository' => $this->repository() ? $this->repository()->getId() : null,
            'environment' => $this->environment() ? $this->environment()->id() : null,
        ];

        return $json;
    }
}
