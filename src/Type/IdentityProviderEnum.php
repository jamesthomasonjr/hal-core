<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

class IdentityProviderEnum
{
    use EnumTrait;

    const ERR_INVALID = '"%s" is not a valid identity provider option.';

    const TYPE_INTERNAL = 'internal';
    const TYPE_LDAP = 'ldap';
    const TYPE_GITHUB = 'gh';
    const TYPE_GITHUB_ENTERPRISE = 'ghe';
    const TYPE_PING_FEDERATE = 'ping';

    /**
     * @return string
     */
    public static function defaultOption()
    {
        return self::TYPE_INTERNAL;
    }

    /**
     * @return string[]
     */
    public static function options()
    {
        return [
            self::TYPE_INTERNAL,
            self::TYPE_LDAP,
            self::TYPE_GITHUB,
            self::TYPE_GITHUB_ENTERPRISE,
            self::TYPE_PING_FEDERATE,
        ];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function format($type)
    {
        switch ($type) {
            case self::TYPE_INTERNAL:
                return 'Internal';

            case self::TYPE_LDAP:
                return 'LDAP';

            case self::TYPE_GITHUB:
                return 'GitHub.com';

            case self::TYPE_GITHUB_ENTERPRISE:
                return 'GitHub Ent.';

            case self::TYPE_PING_FEDERATE:
                return 'Ping Federate';

            default:
                return 'Unknown';
        }
    }
}
