<?php

namespace QL\Hal\Core\Entity;

/**
 *  Token Entity
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @Entity(repositoryClass="QL\Hal\Core\Entity\Repository\TokenRepository")
 *  @Table(name="Tokens")
 */
class Token
{
    /**
     * The token id
     *
     * @var int
     * @Id @Column(name="TokenId", type="integer", unique=true)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *  The token value
     *
     *  @var string
     *  @Column(name="TokenValue", type="string", length=64)
     */
    private $value;

    /**
     *  The token label
     *
     *  @var string
     *  @Column(name="TokenLabel", type="string", length=128)
     */
    private $label;

    /**
     *  The token user owner (if a user)
     *
     *  @var null|User
     *  @ManyToOne(targetEntity="User", inversedBy="tokens")
     *  @JoinColumn(name="UserId", referencedColumnName="UserId", nullable=true)
     */
    private $user;

    /**
     *  The token consumer owner (if a consumer)
     *
     *  @var null|Consumer
     *  @ManyToOne(targetEntity="Consumer", inversedBy="tokens")
     *  @JoinColumn(name="ConsumerId", referencedColumnName="ConsumerId", nullable=true)
     */
    private $consumer;

    /**
     * @return null|Consumer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     * @param null|Consumer $consumer
     */
    public function setConsumer($consumer)
    {
        $this->consumer = $consumer;
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
     * @return null|User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param null|User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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


}