<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Type\UserPermissionEnum;
use Hal\Core\Utility\EntityIDTrait;
use JsonSerializable;

/**
 * Right now this is per user, but eventually we need to support permission roles.
 *
 * Instead of users having permission(s)
 *
 * Roles would have permission(s) and Users have role(s) AND individual permission(s).
 */
class UserPermission implements JsonSerializable
{
    use EntityIDTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var User
     */
    protected $user;

    /**
     * The application this permission is restricted to. Optional.
     *
     * @var Application|null
     */
    protected $application;

    /**
     * The organization this permission is restricted to. Optional.
     *
     * @var Organization|null
     */
    protected $organization;

    /**
     * The environment this permission is restricted to. Optional.
     *
     * @var Environment|null
     */
    protected $environment;

    /**
     * @param string $id
     * @param string $type
     */
    public function __construct($id = '', $type = '')
    {
        $this->id = $id ?: $this->generateEntityID();
        $this->type = $type ? UserPermissionEnum::ensureValid($type) : UserPermissionEnum::defaultOption();

        $this->user = null;
        $this->application = null;
        $this->organization = null;

        $this->environment = null;
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
    public function type()
    {
        return $this->type;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return Application|null
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return Organization|null
     */
    public function organization()
    {
        return $this->organization;
    }

    /**
     * @return Environment|null
     */
    public function environment()
    {
        return $this->environment;
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
     * @param string $type
     *
     * @return self
     */
    public function withType($type)
    {
        $this->type = UserPermissionEnum::ensureValid($type);
        return $this;
    }

    /**
     * @param User $user
     *
     * @return self
     */
    public function withUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Application|null $application
     *
     * @return self
     */
    public function withApplication(Application $application = null)
    {
        $this->application = $application;
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
     * @param Environment|null $environment
     *
     * @return self
     */
    public function withEnvironment(Environment $environment = null)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),
            'type' => $this->type(),

            'user_id' => $this->user() ? $this->user()->id() : null,
            'application_id' => $this->application() ? $this->application()->id() : null,
            'organization_id' => $this->organization() ? $this->organization()->id() : null,

            'environment_id' => $this->environment() ? $this->environment()->id() : null
        ];

        return $json;
    }
}
