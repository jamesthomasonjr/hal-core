<?php
# src/QL/Hal/Core/Entity/Consumer.php

namespace QL\Hal\Core\Entity;

/**
 *  Consumer Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\ConsumerRepository")
 *  @Table(name="Consumers")
 */
class Consumer
{
    /**
     *  The consumer id
     *
     *  @var integer
     *  @Id @Column(name="ConsumerId", type="integer", unique=true)
     *  @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *  The consumer key
     *
     *  @var string
     *  @Column(name="ConsumerKey", type="string", length=24)
     */
    private $key;

    /**
     *  The consumer name
     *
     *  @var string
     *  @Column(name="ConsumerName", type="string", length=48)
     */
    private $name;

    /**
     *  The consumer secret
     *
     *  @var string
     *  @Column(name="ConsumerSecret", type="string", length=128)
     */
    private $secret;

    /**
     *  Set the consumer id
     *
     *  @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *  Get the consumer id
     *
     *  @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Set the consumer key
     *
     *  @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     *  Get the consumer key
     *
     *  @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     *  Set the consumer name
     *
     *  @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *  Get the consumer name
     *
     *  @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *  Set the consumer secret
     *
     *  @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     *  Get the consumer secret
     *
     *  @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }
}
