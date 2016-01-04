<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use JsonSerializable;

class EncryptedProperty implements JsonSerializable
{
    /**
     * @type string
     */
    protected $id;

    /**
     * @type string
     */
    protected $name;

    /**
     * @type array
     */
    protected $data;

    /**
     * @type Application
     */
    protected $application;

    /**
     * The environment the encrypted property is for (NULL for all)
     *
     * @type Environment|null
     */
    protected $environment;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->name = '';
        $this->data = '';

        $this->application = null;
        $this->environment = null;
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
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return Environment|null
     */
    public function environment()
    {
        return $this->environment;
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
     * @param string $data
     *
     * @return self
     */
    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param Application $application
     *
     * @return self
     */
    public function withApplication(Application $application)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @param Environment $environment
     *
     * @return self
     */
    public function withEnvironment(Environment $environment)
    {
        $this->environment = $environment;
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
            // 'data' => $this->data(),
            'data' => '**ENCRYPTED**',

            'application' => $this->application() ? $this->application()->id() : null,
            'environment' => $this->environment() ? $this->environment()->id() : null,
        ];

        return $json;
    }
}
