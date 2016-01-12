<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Entity;

use DateTime;
use JsonSerializable;
use QL\Hal\Core\Entity\User;
use QL\MCP\Common\Time\TimePoint;

class Schema implements JsonSerializable
{
    const DEFAULT_IS_SECURE = true;

    /**
     * @var string
     */
    protected $id;
    protected $key;
    protected $dataType;
    protected $description;

    /**
     * @var TimePoint|null
     */
    protected $created;

    /**
     * @var bool
     */
    protected $isSecure;

    /**
     * @var Application
     */
    protected $application;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->key = '';

        $this->dataType = '';
        $this->description = '';
        $this->isSecure = static::DEFAULT_IS_SECURE;

        $this->created = null;
        $this->application = null;
        $this->user = null;
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
    public function key()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function dataType()
    {
        return $this->dataType;
    }

    /**
     * @return string
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isSecure()
    {
        return $this->isSecure;
    }

    /**
     * @return TimePoint|null
     */
    public function created()
    {
        return $this->created;
    }

    /**
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return User|null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $key
     *
     * @return self
     */
    public function withKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $dataType
     *
     * @return self
     */
    public function withDataType($dataType)
    {
        $this->dataType = $dataType;
        return $this;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function withDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param bool $isSecure
     *
     * @return self
     */
    public function withIsSecure($isSecure)
    {
        $this->isSecure = (bool) $isSecure;
        return $this;
    }

    /**
     * @param TimePoint $created
     *
     * @return self
     */
    public function withCreated(TimePoint $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @param Application $application
     *
     * @return self
     */
    public function withApplication(Application $application)
    {
        $this->application = $application;
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
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),
            'key' => $this->key(),
            'dataType' => $this->dataType(),
            'description' => $this->description(),

            'isSecure' => $this->isSecure(),

            'created' => $this->created(),

            'application' => $this->application() ? $this->application()->id() : null,
            'user' => $this->user() ? $this->user()->id() : null
        ];

        return $json;
    }
}
