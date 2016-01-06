<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use QL\MCP\Common\Time\TimePoint;

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

        $this->status = 'Waiting';
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
     * @return string
     */
    public function isPending()
    {
        return in_array($this->status(), ['Waiting', 'Building'], true);
    }

    /**
     * @return string
     */
    public function isFinished()
    {
        return in_array($this->status(), ['Error', 'Removed', 'Success'], true);
    }

    /**
     * @return string
     */
    public function isSuccess()
    {
        return $this->status() === 'Success';
    }

    /**
     * @return string
     */
    public function isFailure()
    {
        return $this->status() === 'Error';
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
