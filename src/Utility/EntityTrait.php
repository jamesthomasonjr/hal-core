<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use QL\MCP\Common\Time\TimePoint;

trait EntityTrait
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
     * @param string $id
     * @param TimePoint|null $created
     *
     * @return void
     */
    private function initializeEntity($id, $created)
    {
        $this->id = $id ?: $this->generateEntityID();
        $this->created = $created ?: $this->generateCreatedTime();
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return TimePoint
     */
    public function created(): TimePoint
    {
        return $this->created;
    }
}
