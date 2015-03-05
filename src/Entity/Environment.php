<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

class Environment
{
    /**
     * The environment id
     *
     * @var integer
     */
    protected $id;

    /**
     * The environment key
     *
     * @var string
     */
    protected $key;

    /**
     * The environment display order
     *
     * @var integer
     */
    protected $order;

    /**
     * Set the environment id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the environment id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the environment key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get the environment key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the environment display order
     *
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Get the environment display order
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }
}
