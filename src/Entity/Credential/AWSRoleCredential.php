<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\Credential;

use JsonSerializable;

class AWSRoleCredential implements JsonSerializable
{
    /**
     * @var string
     */
    protected $account;

    /**
     * @var string
     */
    protected $role;

    /**
     * @param string $account
     * @param string $role
     */
    public function __construct($account = '', $role = '')
    {
        $this->account = $account;
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function account()
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function role()
    {
        return $this->role;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'account' => $this->account(),
            'role' => $this->role(),
        ];

        return $json;
    }
}
