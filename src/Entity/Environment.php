<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Utility\EntityIDTrait;
use JsonSerializable;

class Environment implements JsonSerializable
{
    use EntityIDTrait;

    /**
     * @var string
     */
    protected $id;
    protected $name;

    /**
     * @var bool
     */
    protected $isProduction;

    /**
     * @param string $id
     * @param string $name
     */
    public function __construct($id = '', $name = '')
    {
        $this->id = $id ?: $this->generateEntityID();
        $this->name = $name ?: '';

        $this->isProduction = false;
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
    public function name()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isProduction()
    {
        return $this->isProduction;
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
     * @param string $name
     *
     * @return self
     */
    public function withName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param bool $isProduction
     *
     * @return self
     */
    public function withIsProduction($isProduction)
    {
        $this->isProduction = (bool) $isProduction;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'name' => $this->name(),
            'is_production' => $this->isProduction(),
        ];

        return $json;
    }
}
