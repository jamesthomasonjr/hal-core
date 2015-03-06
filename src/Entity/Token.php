<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use JsonSerializable;

class Token implements JsonSerializable
{
    /**
     * The token id
     *
     * @var int
     */
    protected $id;

    /**
     * The token value
     *
     * @var string
     */
    protected $value;

    /**
     * The token label
     *
     * @var string
     */
    protected $label;

    /**
     * The token user owner (if a user)
     *
     * @var User|null
     */
    protected $user;

    /**
     * The token consumer owner (if a consumer)
     *
     * @var Consumer|null
     */
    protected $consumer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->value = '';
        $this->label = '';

        $this->user = null;
        $this->consumer = null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user|null
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Consumer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     * @param Consumer $consumer
     */
    public function setConsumer($consumer)
    {
        $this->consumer = $consumer;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->getId(),

            'label' => $this->getLabel(),
            'value' => $this->getValue(),

            'user' => $this->getUser() ? $this->getUser()->getId() : null,
            'consumer' => $this->getConsumer() ? $this->getConsumer()->getId() : null,
        ];

        return $json;
    }
}
