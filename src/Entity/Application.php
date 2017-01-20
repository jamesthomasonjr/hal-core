<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Entity\Application\GitHubApplication;
use Hal\Core\Utility\EntityIDTrait;
use JsonSerializable;

class Application implements JsonSerializable
{
    use EntityIDTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $identifier;
    protected $name;

    /**
     * @var GitHubApplication
     */
    protected $gitHub;

    /**
     * @var Organization|null
     */
    protected $organization;

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

        $this->gitHub = new GitHubApplication;

        $this->organization = null;
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
     * @return Organization|null
     */
    public function organization()
    {
        return $this->organization;
    }

    /**
     * @return GitHubApplication
     */
    public function gitHub()
    {
        return $this->gitHub;
    }

    /**
     * @param int $id
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
     * @param GitHubApplication $gitHub
     *
     * @return self
     */
    public function withGitHub(GitHubApplication $gitHub)
    {
        $this->gitHub = $gitHub;
        return $this;
    }

    /**
     * @param Organization|null $organization
     *
     * @return self
     */
    public function withOrganization(Organization $organization = null)
    {
        $this->organization = $organization;
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
            'name' => $this->name(),

            'github' => $this->gitHub(),

            'organization_id' => $this->organization() ? $this->organization()->id() : null,
        ];

        return $json;
    }
}
