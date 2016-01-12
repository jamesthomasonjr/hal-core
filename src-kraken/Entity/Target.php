<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Entity;

use JsonSerializable;

class Target implements JsonSerializable
{
    /**
     * @var string
     */
    protected $id;
    protected $key;

    /**
     * @var Application
     */
    protected $application;

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->key = '';

        $this->application = null;
        $this->environment = null;
        $this->configuration = null;
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
    public function key()
    {
        return $this->key;
    }

    /**
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return Environment
     */
    public function environment()
    {
        return $this->environment;
    }

    /**
     * @return Configuration
     */
    public function configuration()
    {
        return $this->configuration;
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
    public function withKey($key)
    {
        $this->key = $key;
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
     * @param Application $environment
     *
     * @return self
     */
    public function withEnvironment(Environment $environment)
    {
        $this->environment = $environment;
        return $this;
    }


    /**
     * @param Configuration $configuration
     *
     * @return self
     */
    public function withConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),
            'key' => $this->key(),

            'application' => $this->application() ? $this->application()->id() : null,
            'environment' => $this->environment() ? $this->environment()->id() : null,
            'configuration' => $this->configuration() ? $this->configuration()->id() : null
        ];

        return $json;
    }
}
