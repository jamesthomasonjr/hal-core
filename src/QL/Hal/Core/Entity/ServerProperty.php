<?php
# src/QL/Hal/Core/Entity/ServerProperty.php

namespace QL\Hal\Core\Entity;

/**
 *  Server Property Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\ServerPropertyRepository")
 *  @Table(name="ServerProperties")
 */
class ServerProperty
{
    /**
     *  The server property id
     *
     *  @var int
     *  @Id @Column(name="ServerPropertyId", type="integer", unique=true)
     *  @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *  The server property name
     *
     *  @var string
     *  @Column(name="ServerPropertyName", type="string", length=24)
     */
    private $name;

    /**
     *  The server property value
     *
     *  @var string
     *  @Column(name="ServerPropertyValue", type="string", length=16)
     */
    private $value;

    /**
     *  @var Server
     *  @ManyToOne(targetEntity="Server", inversedBy="properties")
     *  @JoinColumn(name="ServerId", referencedColumnName="ServerId")
     */
    private $server;

    /**
     *  Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->name = null;
        $this->version = null;
        $this->server = null;
    }

    /**
     *  Set the server property id
     *
     *  @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *  Get the server property id
     *
     *  @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Set the server property name
     *
     *  @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *  Get the server property name
     *
     *  @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *  Set the server property value
     *
     *  @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     *  Get the server property value
     *
     *  @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *  Set the server property server
     *
     *  @param Server $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     *  Get the server property server
     *
     *  @return Server
     */
    public function getServer()
    {
        return $this->server;
    }


}
