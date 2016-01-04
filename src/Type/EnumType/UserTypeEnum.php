<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Type\EnumType;

use Doctrine\DBAL\Types\Type as BaseType;

class UserTypeEnum extends BaseType
{
    const TYPE_NORMAL = 'pleb';
    const TYPE_LEAD = 'lead';
    const TYPE_ADMIN = 'btn_pusher';
    const TYPE_SUPER = 'super';

    use EnumTypeTrait;

    /**
     * The enum data type
     */
    const TYPE = 'usertypeenum';

    /**
     * The enum allowed values
     *
     * @return array
     */
    public static function values()
    {
        return [
            self::TYPE_NORMAL,
            self::TYPE_LEAD,
            self::TYPE_ADMIN,
            self::TYPE_SUPER,
        ];
    }
}
