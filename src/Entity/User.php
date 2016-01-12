<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

class User implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $handle;
    protected $name;
    protected $email;

    /**
     * The current user status
     *
     * @var boolean
     */
    protected $isActive;

    /**
     * The github access token for the user
     *
     * @var string
     */
    protected $githubToken;

    /**
     * @var ArrayCollection
     */
    protected $tokens;

    /**
     * @var UserSettings
     */
    protected $settings;

    /**
     * @param int $id
     */
    public function __construct($id = null)
    {
        // from ldap
        $this->id = $id;
        $this->handle = '';
        $this->name = '';
        $this->email = '';

        // hal settings
        $this->isActive = false;
        $this->githubToken = '';

        $this->settings = null;
        $this->tokens = new ArrayCollection;
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
     * @return ArrayCollection
     */
    public function tokens()
    {
        return $this->tokens;
    }

    /**
     * @return UserSettings|null
     */
    public function settings()
    {
        return $this->settings;
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
     * @param bool $isActive
     *
     * @return self
     */
    public function withIsActive($isActive)
    {
        $this->isActive = (bool) $isActive;
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
            'isActive' => $this->isActive(),
            // 'githubToken' => $this->githubToken(),
        ];

        return $json;
    }
}
