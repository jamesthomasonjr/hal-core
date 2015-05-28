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
    protected $key;

    /**
     * @type bool
     */
    protected $isProduction;

    public function __construct()
    {
        $this->id = null;
        $this->key = '';
        $this->isProduction = false;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param bool $isProduction
     */
    public function setIsProduction($isProduction)
    {
        $this->isProduction = (bool) $isProduction;
    }

    /**
     * @return bool
     */
    public function getIsProduction()
    {
        return $this->isProduction;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->getId(),

            'identifier' => $this->getKey(),
            'isProduction' => $this->getIsProduction(),
        ];

        return $json;
    }
}
