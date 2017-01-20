<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Hal\Core\Utility\EntityIDTrait;
use JsonSerializable;

class User implements JsonSerializable
{
    use EntityIDTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;
    protected $name;
    protected $email;

    /**
     * @var boolean
     */
    protected $isDisabled;

    /**
     * @var ArrayCollection
     */
    protected $tokens;

    /**
     * @var UserSettings
     */
    protected $settings;

    /**
     * @param string $id
     * @param string $username
     */
    public function __construct($id = '', $username = '')
    {
        $this->id = $id ?: $this->generateEntityID();
        $this->username = $username ?: '';

        $this->name = '';
        $this->email = '';

        $this->isDisabled = false;

        $this->settings = (new UserSettings)->withUser($this);
        $this->tokens = new ArrayCollection;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function username()
    {
        return $this->username;
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
    public function isDisabled()
    {
        return $this->isDisabled;
    }

    /**
     * @return ArrayCollection
     */
    public function tokens()
    {
        return $this->tokens;
    }

    /**
     * @return UserSettings
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
    public function withID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $username
     *
     * @return self
     */
    public function withUsername($username)
    {
        $this->username = $username;
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
     * @param bool $isDisabled
     *
     * @return self
     */
    public function withIsDisabled($isDisabled)
    {
        $this->isDisabled = (bool) $isDisabled;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'username' => $this->username(),
            'name' => $this->name(),
            'email' => $this->email(),

            'is_disabled' => $this->isDisabled(),
        ];

        return $json;
    }
}
