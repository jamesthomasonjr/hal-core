<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use MCP\DataType\Time\TimePoint;

class Push implements JsonSerializable
{
    /**
     * @type string
     */
    protected $id;

    /**
     * @type TimePoint|null
     */
    protected $created;
    protected $start;
    protected $end;

    /**
     * @type string
     */
    protected $status;

    /**
     * @type User
     */
    protected $user;

    /**
     * @type Build
     */
    protected $build;

    /**
     * @type Deployment
     */
    protected $deployment;

    /**
     * @type Application
     */
    protected $application;

    /**
     * @type ArrayCollection
     */
    protected $logs;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->created = null;
        $this->start = null;
        $this->end = null;
        $this->status = '';

        $this->user = null;
        $this->build = null;
        $this->deployment = null;
        $this->application = null;
        $this->logs = new ArrayCollection;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return TimePoint|null
     */
    public function created()
    {
        return $this->created;
    }

    /**
     * @return TimePoint|null
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * @return TimePoint|null
     */
    public function end()
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return Build
     */
    public function build()
    {
        return $this->build;
    }

    /**
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return Deployment
     */
    public function deployment()
    {
        return $this->deployment;
    }

    /**
     * @return ArrayCollection
     */
    public function logs()
    {
        return $this->logs;
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
     * @param TimePoint|null $created
     *
     * @return self
     */
    public function withCreated(TimePoint $created = null)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @param TimePoint|null $start
     *
     * @return self
     */
    public function withStart(TimePoint $start = null)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @param TimePoint|null $end
     *
     * @return self
     */
    public function withEnd(TimePoint $end = null)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function withStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param Build $build
     *
     * @return self
     */
    public function withBuild(Build $build)
    {
        $this->build = $build;
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
     * @param Deployment $deployment
     *
     * @return self
     */
    public function withDeployment(Deployment $deployment)
    {
        $this->deployment = $deployment;
        return $this;
    }

    /**
     * @param User $user
     *
     * @return self
     */
    public function withUser(User $user = null)
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

            'created' => $this->created(),
            'start' => $this->start(),
            'end' => $this->end(),

            'status' => $this->status(),

            'user' => $this->user() ? $this->user()->id() : null,
            'build' => $this->build() ? $this->build()->id() : null,
            'deployment' => $this->deployment() ? $this->deployment()->id() : null,
            'application' => $this->application() ? $this->application()->id() : null,

            // 'logs' => $this->logs() ? $this->logs()->getKeys() : []
        ];

        return $json;
    }
}
