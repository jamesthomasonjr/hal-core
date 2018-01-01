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
        ];
    }
}
