<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Utility\EntityIDTrait;
use JsonSerializable;

class UserToken implements JsonSerializable
{
    use EntityIDTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;
    protected $value;

    /**
     * Token is created on behalf of a user.
     *
     * @var User|null
     */
    protected $user;

    /**
     * Token is created on behalf of an organization. (Not currently used)
     *
     * @var Organization|null
     */
    protected $organization;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id ?: $this->generateEntityID();

        $this->name = '';
        $this->value = '';

        $this->user = null;
        $this->organization = null;
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
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return User|null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return Organization|null
     */
    public function organization()
    {
        return $this->organization;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function withID($id)
    {
        $this->id = $id;
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
     * @param string $value
     *
     * @return self
     */
    public function withValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param User|null $user
     *
     * @return self
     */
    public function withUser(User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Organization|null $organization
     *
     * @return self
     */
    public function withOrganization(Organization $organization = null)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'name' => $this->name(),
            'value' => $this->value(),

            'user_id' => $this->user() ? $this->user()->id() : null,
            'organization_id' => $this->organization() ? $this->organization()->id() : null,
        ];

        return $json;
    }
}
