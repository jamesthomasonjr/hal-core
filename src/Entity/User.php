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
     * @type int
     */
    protected $id;

    /**
     * @type string
     */
    protected $handle;
    protected $name;
    protected $email;

    /**
     * @type HttpUrl|null
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

    public function __construct()
    {
        // from ldap
        $this->id = null;
        $this->handle = '';
        $this->name = '';
        $this->email = '';
        $this->pictureUrl = null;

        // hal settings
        $this->isActive = false;
        $this->githubToken = '';
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function handle()
    {
        return $this->handle;
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
    public function email()
    {
        return $this->email;
    }

    /**
     * @return HttpUrl|null
     */
    public function pictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @return string
     */
    public function githubToken()
    {
        return $this->githubToken;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $handle
     *
     * @return self
     */
    public function withHandle($handle)
    {
        $this->handle = $handle;
        return $this;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function withName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function withEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param HttpUrl $pictureUrl
     *
     * @return self
     */
    public function withPictureUrl(HttpUrl $pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
        return $this;
    }

    /**
     * @param bool $isActive
     *
     * @return self
     */
    public function withIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @param string $githubToken
     *
     * @return self
     */
    public function withGithubToken($githubToken)
    {
        $this->githubToken = $githubToken;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'handle' => $this->handle(),
            'name' => $this->name(),
            'email' => $this->email(),
            'url' => $this->pictureUrl() ? $this->pictureUrl()->asString() : null,
            'isActive' => $this->isActive(),
            // 'githubToken' => $this->githubToken(),
        ];

        return $json;
    }
}
