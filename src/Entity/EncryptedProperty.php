<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

class EncryptedProperty
{
    /**
     * The encrypted property id
     *
     * @var varchar
     */
    protected $id;

    /**
     * The property name
     *
     * @var string
     */
    protected $name;

    /**
     * The encrypted data
     *
     * @var array
     */
    protected $data;

    /**
     * The repository the encrypted property is for
     *
     * @var Repository
     */
    protected $repository;

    /**
     * The environment the encrypted property is for (NULL for all)
     *
     * @var Environment
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $key
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param Repository $repository
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }
}
