<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use MCP\DataType\HttpUrl;

/**
 * @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\UserRepository")
 * @Table(name="Users")
 */
class User
{
    /**
     * The common id of the user.
     *
     * @var integer
     * @Id @Column(name="UserId", type="integer", unique=true)
     */
    protected $id;

    /**
     * The handle (username) of the user.
     *
     * @var string
     * @Column(name="UserHandle", type="string", length=32, unique=true)
     */
    protected $handle;

    /**
     * The display name of the user.
     *
     * @var string
     * @Column(name="UserName", type="string", length=128)
     */
    protected $name;

    /**
     * The email address of the user.
     *
     * @var string
     * @Column(name="UserEmail", type="string", length=128)
     */
    protected $email;

    /**
     * The URL of the user picture.
     *
     * @var null|HttpUrl
     * @Column(name="UserPictureUrl", type="url")
     */
    protected $pictureUrl;

    /**
     * All pushes done by the user.
     *
     * @var ArrayCollection
     * @OneToMany(targetEntity="Push", mappedBy="user")
     * @OrderBy({"created" = "DESC"})
     */
    protected $pushes;

    /**
     * All builds done by the user.
     *
     * @var ArrayCollection
     * @OneToMany(targetEntity="Build", mappedBy="user")
     * @OrderBy({"created" = "DESC"})
     */
    protected $builds;

    /**
     * The current user status
     *
     * @var boolean
     * @Column(name="UserIsActive", type="boolean")
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
     * Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->handle = null;
        $this->name = null;
        $this->email = null;
        $this->pictureUrl = null;
        $this->pushes = new ArrayCollection();
        $this->builds = new ArrayCollection();
        $this->isActive = false;
    }

    /**
     * Set the user id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the user id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the user handle (username)
     *
     * @param string $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }

    /**
     * Get the user handle (username)
     *
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * Set the user display name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the user display name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the user email address
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get the user email address
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the user picture url
     *
     * @param HttpUrl $pictureUrl
     */
    public function setPictureUrl(HttpUrl $pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
    }

    /**
     * Get the user picture url
     *
     * @return null|HttpUrl
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * Set the the user builds
     *
     * @param ArrayCollection $builds
     */
    public function setBuilds(ArrayCollection $builds)
    {
        $this->builds = $builds;
    }

    /**
     * Get the user builds
     *
     * @return ArrayCollection
     */
    public function getBuilds()
    {
        return $this->builds;
    }

    /**
     * Set the user pushes
     *
     * @param ArrayCollection $pushes
     */
    public function setPushes(ArrayCollection $pushes)
    {
        $this->pushes = $pushes;
    }

    /**
     * Get the user pushes
     *
     * @return ArrayCollection
     */
    public function getPushes()
    {
        return $this->pushes;
    }

    /**
     * Set the user status
     *
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Get the user status
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * Set the user tokens
     *
     * @param ArrayCollection $tokens
     */
    public function setTokens(ArrayCollection $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Get the user tokens
     *
     * @return ArrayCollection
     */
    public function getTokens()
    {
        return $this->tokens;
    }
}
