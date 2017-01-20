<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Hal\Core\Utility\EntityIDTrait;
use JsonSerializable;

class Organization implements JsonSerializable
{
    use EntityIDTrait;

    /**
     * @var string
     */
    protected $id;
    protected $identifier;
    protected $name;

    /**
     * @param string $id
     * @param string $identifier
     * @param string $name
     */
    public function __construct($id = '', $identifier = '', $name = '')
    {
        $this->id = $id ?: $this->generateEntityID();

        $this->identifier = $identifier ?: '';
        $this->name = $name ?: '';
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
    public function identifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
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
     * @param string $identifier
     *
     * @return self
     */
    public function withIdentifier($identifier)
    {
        $this->identifier = $identifier;
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
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'identifier' => $this->identifier(),
            'name' => $this->name()
        ];

        return $json;
    }
}
