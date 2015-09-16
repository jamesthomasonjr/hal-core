<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

class Application implements JsonSerializable
{
    /**
     * @type int
     */
    protected $id;

    /**
     * @type string
     */
    protected $key;
    protected $name;

    /**
     * @type string
     */
    protected $githubOwner;
    protected $githubRepo;

    /**
     * @type string
     */
    protected $email;

    /**
     * @type string
     */
    protected $buildCmd;
    protected $buildTransformCmd;
    protected $prePushCmd;
    protected $postPushCmd;

    /**
     * @type Group
     */
    protected $group;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = null;
        $this->key = null;
        $this->name = null;

        $this->githubOwner = '';
        $this->githubRepo = '';
        $this->email = '';

        $this->buildCmd = '';
        $this->buildTransformCmd = '';
        $this->prePushCmd = '';
        $this->postPushCmd = '';

        $this->group = null;
    }

    /**
     * @return int
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
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function githubOwner()
    {
        return $this->githubOwner;
    }

    /**
     * @return string
     */
    public function githubRepo()
    {
        return $this->githubRepo;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return Group
     */
    public function group()
    {
        return $this->group;
    }

    /**
     * @param int $id
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
     * @param string $githubOwner
     *
     * @return self
     */
    public function withGithubOwner($githubOwner)
    {
        $this->githubOwner = $githubOwner;
        return $this;
    }
    /**
     * @param string $githubRepo
     *
     * @return self
     */
    public function withGithubRepo($githubRepo)
    {
        $this->githubRepo = $githubRepo;
        return $this;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function withEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param Group $group
     *
     * @return self
     */
    public function withGroup(Group $group)
    {
        $this->group = $group;
        return $this;
    }

    public function getBuildCmd() {return $this->buildCmd;}
    public function getBuildTransformCmd() {return $this->buildTransformCmd;}
    public function getPrePushCmd() {return $this->prePushCmd;}
    public function getPostPushCmd() {return $this->postPushCmd;}

    public function setBuildCmd($buildCmd) {$this->buildCmd = $buildCmd;}
    public function setBuildTransformCmd($buildTransformCmd) {$this->buildTransformCmd = $buildTransformCmd;}
    public function setPrePushCmd($prePushCmd) {$this->prePushCmd = $prePushCmd;}
    public function setPostPushCmd($postPushCmd) {$this->postPushCmd = $postPushCmd;}

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'identifier' => $this->key(),
            'name' => $this->name(),
            'githubOwner' => $this->githubOwner(),
            'githubRepo' => $this->githubRepo(),
            'email' => $this->email(),

            'group' => $this->group() ? $this->group()->id() : null,
        ];

        return $json;
    }
}
