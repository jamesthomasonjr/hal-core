<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Kraken\Core\Entity;

use JsonSerializable;

class Environment implements JsonSerializable
{
    /**
     * @type string
     */
    protected $id;
    protected $name;
    protected $consulServiceURL;
    protected $consulToken;

    /**
     * @type string
     */
    protected $qksServiceURL;
    protected $qksClientID;
    protected $qksClientSecret;

    /**
     * @type bool
     */
    protected $isProduction;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->name = '';

        $this->consulServiceURL = '';
        $this->consulToken = '';

        $this->qksServiceURL = '';
        $this->qksClientID = '';
        $this->qksClientSecret = '';

        $this->isProduction = false;
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
     * @return bool
     */
    public function isProduction()
    {
        return $this->isProduction;
    }

    /**
     * @return string
     */
    public function consulServiceURL()
    {
        return $this->consulServiceURL;
    }

    /**
     * @return string
     */
    public function consulToken()
    {
        return $this->consulToken;
    }

    /**
     * @return string
     */
    public function qksServiceURL()
    {
        return $this->qksServiceURL;
    }

    /**
     * @return string
     */
    public function qksClientID()
    {
        return $this->qksClientID;
    }

    /**
     * @return string
     */
    public function qksClientSecret()
    {
        return $this->qksClientSecret;
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
     * @param bool $isProduction
     *
     * @return self
     */
    public function withIsProduction($isProduction)
    {
        $this->isProduction = $isProduction;
        return $this;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function withConsulServiceURL($url)
    {
        $this->consulServiceURL = $url;
        return $this;
    }

    /**
     * @param string $token
     *
     * @return self
     */
    public function withConsulToken($token)
    {
        $this->consulToken = $token;
        return $this;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function withQKSServiceURL($url)
    {
        $this->qksServiceURL = $url;
        return $this;
    }

    /**
     * @param string $clientID
     *
     * @return self
     */
    public function withQKSClientID($clientID)
    {
        $this->qksClientID = $clientID;
        return $this;
    }

    /**
     * @param string $clientSecret
     *
     * @return self
     */
    public function withQKSClientSecret($clientSecret)
    {
        $this->qksClientSecret = $clientSecret;
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
            'isProduction' => $this->isProduction(),

            'consulServiceURL' => $this->consulServiceURL(),
            // 'consulToken' => $this->consulToken(),

            'qksServiceURL' => $this->qksServiceURL(),
            'qksClientID' => $this->qksClientID(),
            // 'qksClientSecret' => $this->qksClientSecret()
        ];

        return $json;
    }
}
