<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Type\AuditActionEnum;
use Hal\Core\Utility\EntityIDTrait;
use Hal\Core\Utility\TimeCreatedTrait;
use QL\MCP\Common\Time\TimePoint;

/**
 * Audit Events must be complete denormalized from DB entities.
 */
class AuditEvent
{
    use EntityIDTrait;
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
     * @var string
     */
    protected $action;
    protected $owner;

    /**
     * @var string
     */
    protected $entity;
    protected $data;

    /**
     * @param string $id
     * @param TimePoint|null $created
     */
    public function __construct($id = '', TimePoint $created = null)
    {
        $this->id = $id ?: $this->generateEntityID();
        $this->created = $created ?: $this->generateCreatedTime();

        $this->action = AuditActionEnum::defaultOption();
        $this->owner = '';

        $this->entity = '';
        $this->data = '';
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
     * @return string
     */
    public function owner()
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function entity()
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function data()
    {
        return $this->data;
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
     * @param TimePoint $created
     *
     * @return self
     */
    public function withCreated(Timepoint $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @param string $action
     *
     * @return self
     */
    public function withAction($action)
    {
        $this->action = AuditActionEnum::ensureValid($action);
        return $this;
    }

    /**
     * @param string $owner
     *
     * @return self
     */
    public function withOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @param string $entity
     *
     * @return self
     */
    public function withEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param string $data
     *
     * @return self
     */
    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }
}
