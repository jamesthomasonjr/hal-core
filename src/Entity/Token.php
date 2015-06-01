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
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function label()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $label
     *
     * @return self
     */
    public function withLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function withValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param User $user
     *
     * @return self
     */
    public function withUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'label' => $this->label(),
            'value' => $this->value(),

            'user' => $this->user()->id(),
        ];

        return $json;
    }
}
