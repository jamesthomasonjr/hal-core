<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Hal\Core\Utility\EntityTrait;
use Hal\Core\Utility\ParameterTrait;
use Hal\Core\Type\JobEnum;
use Hal\Core\Type\JobStatusEnum;
use JsonSerializable;
use QL\MCP\Common\Time\TimePoint;

class Job implements JsonSerializable
{
    use EntityTrait;
    use ParameterTrait;

    private const ERR_INVALID_SUBTYPE = 'Invalid job type provided.  Must be type Build or Release.';

    /**
     * @var TimePoint|null
     */
    protected $start;
    protected $end;

    /**
     * @var string
     */
    protected $type;
    protected $status;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * @var ArrayCollection
     */
    protected $artifacts;
    protected $events;
    protected $meta;

    /**
     * @param string $type
     * @param string $id
     * @param TimePoint|null $created
     */
    public function __construct($type = '', $id = '', TimePoint $created = null)
    {
        $this->initializeEntity($id, $created);
        $this->initializeParameters();

        $this->type = $type ? JobEnum::ensureValid($type) : JobEnum::defaultOption();
        $this->status = JobStatusEnum::defaultOption();

        $this->start = null;
        $this->end = null;

        $this->user = null;
        $this->artifacts = new ArrayCollection;
        $this->events = new ArrayCollection;
        $this->meta = new ArrayCollection;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->status;
    }

    /**
     * @return TimePoint|null
     */
    public function start(): ?TimePoint
    {
        return $this->start;
    }

    /**
     * @return TimePoint|null
     */
    public function end(): ?TimePoint
    {
        return $this->end;
    }

    /**
     * @return User|null
     */
    public function user(): ?User
    {
        return $this->user;
    }

    /**
     * @return ArrayCollection
     */
    public function artifacts(): ArrayCollection
    {
        return $this->artifacts;
    }

    /**
     * @return ArrayCollection
     */
    public function events(): ArrayCollection
    {
        return $this->events;
    }

    /**
     * @return ArrayCollection
     */
    public function meta(): ArrayCollection
    {
        return $this->meta;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function withType(string $type): self
    {
        $this->status = JobEnum::ensureValid($type);
        return $this;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function withStatus(string $status): self
    {
        $this->status = JobStatusEnum::ensureValid($status);
        return $this;
    }

    /**
     * @param TimePoint|null $start
     *
     * @return self
     */
    public function withStart(?TimePoint $start): self
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @param TimePoint|null $end
     *
     * @return self
     */
    public function withEnd(?TimePoint $end): self
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @param User|null $user
     *
     * @return self
     */
    public function withUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     */
    public function inProgress(): bool
    {
        return in_array($this->status(), [JobStatusEnum::TYPE_PENDING, JobStatusEnum::TYPE_RUNNING], true);
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return in_array($this->status(), [JobStatusEnum::TYPE_SUCCESS, JobStatusEnum::TYPE_FAILURE, JobStatusEnum::TYPE_REMOVED], true);
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->status() === JobStatusEnum::TYPE_SUCCESS;
    }

    /**
     * @return bool
     */
    public function isFailure(): bool
    {
        return $this->status() === JobStatusEnum::TYPE_FAILURE;
    }

    // @todo add artifact add/remove - arraycollection items should always be removed from the parent
    // @todo add event add/remove - arraycollection items should always be removed from the parent
    // @todo add meta add/remove - arraycollection items should always be removed from the parent

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),
            'created' => $this->created(),

            'type' => $this->type(),
            'status' => $this->status(),
            'parameters' => $this->parameters(),

            'start' => $this->start(),
            'end' => $this->end(),

            'user_id' => $this->user() ? $this->user()->id() : null,

            'artifacts' => $this->artifacts()->toArray(),
            'events' => $this->events()->toArray(),
            'meta' => $this->meta()->toArray()
        ];

        return $json;
    }
}
