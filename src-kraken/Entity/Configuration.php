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

class Configuration implements JsonSerializable
{
    /**
     * @var string
     */
    protected $id;
    protected $audit;

    /**
     * @var bool
     */
    protected $isSuccess;

    /**
     * @var TimePoint|null
     */
    protected $created;

    /**
     * @var Application
     */
    protected $application;

    /**
     * @var Environment
     */
    protected $environment;

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
        $this->audit = '';
        $this->created = null;

        $this->isSuccess = false;

        $this->application = null;
        $this->environment = null;
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
     * @return bool
     */
    public function isSuccess()
    {
        return $this->isSuccess;
    }

    /**
     * @return string
     */
    public function audit()
    {
        return $this->audit;
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
     * @return Environment
     */
    public function environment()
    {
        return $this->environment;
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
     * @param bool $isSuccess
     *
     * @return self
     */
    public function withIsSuccess($isSuccess)
    {
        $this->isSuccess = (bool) $isSuccess;
        return $this;
    }

    /**
     * @param string $auditData
     *
     * @return self
     */
    public function withAudit($auditData)
    {
        $this->audit = $auditData;
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
     * @param Application $environment
     *
     * @return self
     */
    public function withEnvironment(Environment $environment)
    {
        $this->environment = $environment;
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
            'isSuccess' => $this->isSuccess(),
            'audit' => $this->audit(),
            'created' => $this->created(),

            'application' => $this->application() ? $this->application()->id() : null,
            'environment' => $this->environment() ? $this->environment()->id() : null,
            'user' => $this->user() ? $this->user()->id() : null
        ];

        return $json;
    }
}
