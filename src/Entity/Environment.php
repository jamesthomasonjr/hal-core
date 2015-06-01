<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use JsonSerializable;

class Environment implements JsonSerializable
{
    /**
     * @type integer
     */
    protected $id;

    /**
     * @type string
     */
    protected $name;

    /**
     * @type bool
     */
    protected $isProduction;

    public function __construct()
    {
        $this->id = null;
        $this->name = '';
        $this->isProduction = false;
    }

    /**
     * @return integer
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
     * @param integer $id
     *
     * @return self
     */
    public function withId($id)
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
            'isProduction' => $this->isProduction(),
        ];

        return $json;
    }
}
