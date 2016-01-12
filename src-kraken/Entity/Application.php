<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Entity;

use JsonSerializable;
use QL\Hal\Core\Entity\Application as HalApplication;

class Application implements JsonSerializable
{
    /**
     * @var string
     */
    protected $id;
    protected $name;
    protected $coreId;

    /**
     * @var HalApplication|null
     */
    protected $halApplication;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->name = '';
        $this->coreId = '';

        $this->halApplication = null;
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
     * @return string
     */
    public function coreId()
    {
        return $this->coreId;
    }

    /**
     * @return HalApplication|null
     */
    public function halApplication()
    {
        return $this->halApplication;
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
     * @param string $coreId
     *
     * @return self
     */
    public function withCoreId($coreId)
    {
        $this->coreId = $coreId;
        return $this;
    }

    /**
     * @param HalApplication|null $application
     *
     * @return self
     */
    public function withHalApplication(HalApplication $application = null)
    {
        $this->halApplication = $application;
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
            'coreId' => $this->coreId(),

            'halApplication' => $this->halApplication() ? $this->halApplication()->id() : null
        ];

        return $json;
    }
}
