<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use MCP\DataType\HttpUrl;

/**
 *  Subscription Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\SubscriptionRepository")
 *  @Table(name="Subscriptions")
 */
class Subscription
{
    /**
     *  The subscription id
     *
     *  @var int
     *  @Id @Column(name="SubscriptionId", type="integer", unique=true)
     *  @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *  The subscription notification URL
     *
     *  @var null|HttpUrl
     *  @Column(name="SubscriptionUrl", type="url")
     */
    protected $url;

    /**
     *  The subscription event name
     *
     *  @var string
     *  @Column(name="SubscriptionEvent", type="string", length=24)
     */
    protected $event;

    /**
     *  The subscription consumer (owner)
     *
     *  @var Consumer
     *  @ManyToOne(targetEntity="Consumer")
     *  @JoinColumn(name="ConsumerId", referencedColumnName="ConsumerId")
     */
    protected $consumer;

    /**
     *  The subscription repository filter (optional)
     *
     *  @var null|Repository
     *  @ManyToOne(targetEntity="Repository")
     *  @JoinColumn(name="RepositoryId", referencedColumnName="RepositoryId", nullable=true)
     */
    protected $repository;

    /**
     *  The subscription environment filter (optional)
     *
     *  @var null|Environment
     *  @ManyToOne(targetEntity="Environment")
     *  @JoinColumn(name="EnvironmentId", referencedColumnName="EnvironmentId", nullable=true)
     */
    protected $environment;

    /**
     *  The subscription server filter (optional)
     *
     *  @var null|Server
     *  @ManyToOne(targetEntity="Server")
     *  @JoinColumn(name="ServerId", referencedColumnName="ServerId", nullable=true)
     */
    protected $server;

    /**
     *  The subscription group filter (optional)
     *
     *  @var null|Group
     *  @ManyToOne(targetEntity="Group")
     *  @JoinColumn(name="GroupId", referencedColumnName="GroupId", nullable=true)
     */
    protected $group;

    /**
     *  Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->url = null;
        $this->event = null;
        $this->consumer = null;
        $this->repository = null;
        $this->environment = null;
        $this->server = null;
        $this->group = null;
    }

    /**
     *  Set the subscription id
     *
     *  @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *  Get the subscription id
     *
     *  @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Set the subscription notification url
     *
     *  @param HttpUrl $url
     */
    public function setUrl(HttpUrl $url)
    {
        $this->url = $url;
    }

    /**
     *  Get the subscription notification url
     *
     *  @return null|HttpUrl
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *  Set the subscription event name
     *
     *  @param string $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     *  Get the subscription event name
     *
     *  @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     *  Set the subscription consumer
     *
     *  @param Consumer $consumer
     */
    public function setConsumer(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    /**
     *  Get the subscription consumer
     *
     *  @return Consumer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     *  Set the subscription repository filter
     *
     *  @param null|Repository $repository
     */
    public function setRepository(Repository $repository = null)
    {
        $this->repository = $repository;
    }

    /**
     *  Get the subscription repository filter
     *
     *  @return null|Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     *  Set the subscription environment filter
     *
     *  @param null|Environment $environment
     */
    public function setEnvironment(Environment $environment = null)
    {
        $this->environment = $environment;
    }

    /**
     *  Get the subscription environment filter
     *
     *  @return null|Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     *  Set the subscription server filter
     *
     * @param null|Server $server
     */
    public function setServer(Server $server = null)
    {
        $this->server = $server;
    }

    /**
     *  Get the subscription server filter
     *
     *  @return null|Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     *  Set the subscription group filter
     *
     *  @param null|Group $group
     */
    public function setGroup(Group $group = null)
    {
        $this->group = $group;
    }

    /**
     *  Get the subscription group filter
     *
     *  @return null|Group
     */
    public function getGroup()
    {
        return $this->group;
    }
}
