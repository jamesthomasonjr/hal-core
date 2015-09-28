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

class Build implements JsonSerializable
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
    protected $branch;
    protected $commit;

    /**
     * @type User|null
     */
    protected $user;

    /**
     * @type Application
     */
    protected $application;

    /**
     * @type Environment
     */
    protected $environment;

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

        $this->status = null;
        $this->branch = '';
        $this->commit = '';

        $this->user = null;
        $this->repository = null;
        $this->environment = null;

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
     * @return string
     */
    public function branch()
    {
        return $this->branch;
    }

    /**
     * @return string
     */
    public function commit()
    {
        return $this->commit;
    }

    /**
     * @return User|null
     */
    public function user()
    {
        return $this->user;
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
     * @return ArrayCollection
     */
    public function logs()
    {
        return $this->logs;
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
     * @param string $branch
     *
     * @return self
     */
    public function withBranch($branch)
    {
        $this->branch = $branch;
        return $this;
    }

    /**
     * @param string $commit
     *
     * @return self
     */
    public function withCommit($commit)
    {
        $this->commit = $commit;
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
     * @param Environment $environment
     *
     * @return self
     */
    public function withEnvironment(Environment $environment)
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

            'created' => $this->created(),
            'start' => $this->start(),
            'end' => $this->end(),

            'status' => $this->status(),
            'branch' => $this->branch(),
            'commit' => $this->commit(),

            'user' => $this->user() ? $this->user()->id() : null,
            'repository' => $this->application() ? $this->application()->id() : null,
            'environment' => $this->environment() ? $this->environment()->id() : null,

            // 'logs' => $this->logs() ? $this->logs()->getKeys() : []
        ];

        return $json;
    }
}
