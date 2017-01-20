<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Utility\JobIDTrait;
use Hal\Core\Utility\TimeCreatedTrait;
use Hal\Core\Type\JobStatusEnum;
use JsonSerializable;
use QL\MCP\Common\Time\TimePoint;

class Build implements JsonSerializable
{
    use JobIDTrait;
    use TimeCreatedTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var TimePoint
     */
    protected $created;

    /**
     * @var TimePoint|null
     */
    protected $start;
    protected $end;

    /**
     * @var string
     */
    protected $status;
    protected $reference;
    protected $commit;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * @var Application|null
     */
    protected $application;

    /**
     * @var Environment|null
     */
    protected $environment;

    /**
     * @param string $id
     * @param TimePoint $created
     */
    public function __construct($id = '', TimePoint $created = null)
    {
        $this->id = $id ?: $this->generateBuildID();
        $this->created = $created ?: $this->generateCreatedTime();

        $this->status = JobStatusEnum::defaultOption();

        $this->start = null;
        $this->end = null;

        $this->reference = '';
        $this->commit = '';

        $this->user = null;
        $this->application = null;
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
     * @return TimePoint
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
    public function reference()
    {
        return $this->reference;
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
     * @return Application|null
     */
    public function application()
    {
        return $this->application;
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
        $this->status = JobStatusEnum::ensureValid($status);
        return $this;
    }

    /**
     * @param string $ref
     *
     * @return self
     */
    public function withReference($ref)
    {
        $this->reference = $ref;
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
     * @return string
     */
    public function inProgress()
    {
        return in_array($this->status(), [JobStatusEnum::TYPE_PENDING, JobStatusEnum::TYPE_BUILDING], true);
    }

    /**
     * @return string
     */
    public function isFinished()
    {
        return in_array($this->status(), [JobStatusEnum::TYPE_SUCCESS, JobStatusEnum::TYPE_FAILURE, JobStatusEnum::TYPE_REMOVED], true);
    }

    /**
     * @return string
     */
    public function isSuccess()
    {
        return $this->status() === JobStatusEnum::TYPE_SUCCESS;
    }

    /**
     * @return string
     */
    public function isFailure()
    {
        return $this->status() === JobStatusEnum::TYPE_FAILURE;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'created' => $this->created(),
            'status' => $this->status(),

            'start' => $this->start(),
            'end' => $this->end(),

            'reference' => $this->reference(),
            'commit' => $this->commit(),

            'user_id' => $this->user() ? $this->user()->id() : null,
            'application_id' => $this->application() ? $this->application()->id() : null,

            'environment_id' => $this->environment() ? $this->environment()->id() : null,
        ];

        return $json;
    }
}
