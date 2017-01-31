<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\Application;

use JsonSerializable;

class GitHubApplication implements JsonSerializable
{
    /**
     * @var string
     */
    protected $owner;

    /**
     * @var string
     */
    protected $repository;

    /**
     * @param string $owner
     * @param string $repository
     */
    public function __construct($owner = '', $repository = '')
    {
        $this->owner = $owner;
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function owner()
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function repository()
    {
        return $this->repository;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'owner' => $this->owner(),
            'repository' => $this->repository()
        ];

        return $json;
    }
}
