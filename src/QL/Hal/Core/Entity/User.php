<?php

namespace QL\Hal\Core\Entity;

use MCP\DataType\HttpUrl;

/**
 *  User Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\UserRepository")
 *  @Table(name="Users")
 */
class User
{
    /**
     *  The common id of the user.
     *
     *  @var integer
     *  @Id @Column(name="UserId", type="integer", unique=true)
     */
    private $id;

    /**
     *  The handle (username) of the user.
     *
     *  @var string
     *  @Column(name="UserHandle", type="string", length=32, unique=true)
     */
    private $handle;

    /**
     *  The display name of the user.
     *
     *  @var string
     *  @Column(name="UserName", type="string", length=128)
     */
    private $name;

    /**
     *  The email address of the user.
     *
     *  @var string
     *  @Column(name="UserEmail", type="string", length=128)
     */
    private $email;

    /**
     *  The URL of the user picture.
     *
     *  @var null|HttpUrl
     *  @Column(name="UserPictureUrl", type="url")
     */
    private $pictureUrl;

    /**
     *  Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->handle = null;
        $this->name = null;
        $this->email = null;
        $this->pictureUrl = null;
    }

    /**
     *  Set the user id
     *
     *  @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *  Get the user id
     *
     *  @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Set the user handle (username)
     *
     *  @param string $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }

    /**
     *  Get the user handle (username)
     *
     *  @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     *  Set the user display name
     *
     *  @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *  Get the user display name
     *
     *  @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *  Set the user email address
     *
     *  @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     *  Get the user email address
     *
     *  @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *  Set the user picture url
     *
     *  @param HttpUrl $pictureUrl
     */
    public function setPictureUrl(HttpUrl $pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
    }

    /**
     *  Get the user picture url
     *
     *  @return null|HttpUrl
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

}
