<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Hal\Core\Utility\EntityIDTrait;
use JsonSerializable;

class TargetPool implements JsonSerializable
{
    use EntityIDTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * What view does this pool belong to.
     *
     * @var TargetView
     */
    protected $view;

    /**
     * Targets within this pool.
     *
     * @var ArrayCollection
     */
    protected $targets;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id ?: $this->generateEntityID();

        $this->name = '';

        $this->view = null;
        $this->targets = new ArrayCollection;
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
     * @return TargetView
     */
    public function view()
    {
        return $this->view;
    }

    /**
     * @return ArrayCollection
     */
    public function targets()
    {
        return $this->targets;
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
     * @param string $key
     *
     * @return self
     */
    public function withName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param TargetView $view
     *
     * @return self
     */
    public function withView(TargetView $view)
    {
        $this->view = $view;
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

            'view_id' => $this->view() ? $this->view()->id() : null,
        ];

        return $json;
    }
}
