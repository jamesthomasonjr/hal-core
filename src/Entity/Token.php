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
     * @type string
     */
    protected $id;

    /**
     * @type string
     */
    protected $value;

    /**
     * @type string
     */
    protected $label;

    /**
     * @type User
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = '';
        $this->value = '';
        $this->label = '';

        $this->user = null;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
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
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->getId(),

            'label' => $this->getLabel(),
            'value' => $this->getValue(),

            'user' => $this->getUser()->getId(),
        ];

        return $json;
    }
}
