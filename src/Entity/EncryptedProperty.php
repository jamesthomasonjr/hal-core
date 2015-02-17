<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

/**
 * @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\EncryptedPropertyRepository")
 * @Table(name="EncryptedProperties")
 */
class EncryptedProperty
{
    /**
     * The encrypted property id
     *
     * @var varchar
     * @Id @Column(name="EncryptedPropertyId", type="string", length=40)
     */
    protected $id;

    /**
     * The property name
     *
     * @var string
     * @Column(name="EncryptedPropertyName", type="string", length=128)
     */
    protected $name;

    /**
     * The encrypted data
     *
     * @var array
     * @Column(name="EncryptedPropertyData", type="blob")
     */
    protected $data;

    /**
     * The repository the encrypted property is for
     *
     * @var Repository
     * @ManyToOne(targetEntity="Repository")
     * @JoinColumn(name="RepositoryId", referencedColumnName="RepositoryId")
     */
    protected $repository;

    /**
     * The environment the encrypted property is for (NULL for all)
     *
     * @var Environment
     *
     * @ManyToOne(targetEntity="Environment")
     * @JoinColumn(name="EnvironmentId", referencedColumnName="EnvironmentId", nullable=true)
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
