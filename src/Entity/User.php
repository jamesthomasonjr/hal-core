<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use MCP\DataType\HttpUrl;

class User implements JsonSerializable
{
    /**
     * @type integer
     */
    protected $id;

    /**
     * @type string
     */
    protected $handle;

    /**
     * @type string
     */
    protected $name;

    /**
     * @type string
     */
    protected $email;

    /**
     * @type null|HttpUrl
     */
    protected $pictureUrl;

    /**
     * The current user status
     *
     * @type boolean
     */
    protected $isActive;

    /**
     * The github access token for the user
     *
     * @type string
     */
    protected $githubToken;

    /**
     * All tokens for the user.
     *
     * @type ArrayCollection
     */
    protected $tokens;

    public function __construct()
    {
        // from ldap
        $this->id = null;
        $this->handle = null;
        $this->name = null;
        $this->email = null;
        $this->pictureUrl = null;

        // hal settings
        $this->isActive = false;
        $this->githubToken = '';

        // convenience queries
        $this->tokens = new ArrayCollection();
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
     * Set the github access token
     *
     * @param string $githubToken
     */
    public function setGithubToken($githubToken)
    {
        $this->githubToken = $githubToken;
    }

    /**
     * Get the github access token
     *
     * @return string
     */
    public function getGithubToken()
    {
        return $this->githubToken;
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

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->getId(),

            'handle' => $this->getHandle(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'url' => $this->getPictureUrl() ? $this->getPictureUrl()->asString() : null,
            'isActive' => $this->isActive(),
            // 'githubToken' => $this->getGithubToken(),

            // 'tokens' => $this->getTokens() ? $this->getTokens()->getKeys() : [],
        ];

        return $json;
    }
}
