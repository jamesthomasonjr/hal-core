<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

class UserPermissionEnum
{
    use EnumTrait;

    const ERR_INVALID = '"%s" is not a valid permission option.';

    const TYPE_MEMBER = 'member'; // member of (app, org)
    const TYPE_OWNER = 'owner'; // owner of (app, org, or env)
    const TYPE_ADMIN = 'admin'; // admin of (env)
    const TYPE_SUPER = 'super'; // god mode

    /**
     * @return string
     */
    public static function defaultOption()
    {
        return self::TYPE_MEMBER;
    }

    /**
     * @return string[]
     */
    public static function options()
    {
        return [
            self::TYPE_MEMBER,
            self::TYPE_OWNER,
            self::TYPE_ADMIN,
            self::TYPE_SUPER,
        ];
    }
}
