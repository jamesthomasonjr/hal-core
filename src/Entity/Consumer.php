<?php
# src/QL/Hal/Core/Entity/Consumer.php

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\ConsumerRepository")
 * @Table(name="Consumers")
 */
class Consumer
{
    /**
     * The consumer id
     *
     * @var integer
     * @Id @Column(name="ConsumerId", type="integer", unique=true)
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The consumer key
     *
     * @var string
     * @Column(name="ConsumerKey", type="string", length=24)
     */
    protected $key;

    /**
     * The consumer name
     *
     * @var string
     * @Column(name="ConsumerName", type="string", length=48)
     */
    protected $name;

    /**
     * The consumer secret
     *
     * @var string
     * @Column(name="ConsumerSecret", type="string", length=128)
     */
    protected $secret;

    /**
     * The consumer status
     *
     * @var boolean
     * @Column(name="ConsumerIsActive", type="boolean")
     */
    protected $isActive;

    /**
     * All tokens for the user.
     *
     * @var ArrayCollection
     * @OneToMany(targetEntity="Token", mappedBy="user")
     * @OrderBy({"id" = "DESC"})
     */
    protected $tokens;

    /**
     * Set the consumer id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the consumer id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the consumer key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get the consumer key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the consumer name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the consumer name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the consumer secret
     *
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Get the consumer secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set the consumer status
     *
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Get the consumer status
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * Set the consumer tokens
     *
     * @param ArrayCollection $tokens
     */
    public function setTokens(ArrayCollection $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Get the consumer tokens
     *
     * @return ArrayCollection
     */
    public function getTokens()
    {
        return $this->tokens;
    }
}
