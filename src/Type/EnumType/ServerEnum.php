<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Type\EnumType;

use Doctrine\DBAL\Types\Type as BaseType;

class ServerEnum extends BaseType
{
    const TYPE_RSYNC = 'rsync';
    const TYPE_EB = 'eb';
    const TYPE_S3 = 's3';
    const TYPE_CD = 'cd';

    use EnumTypeTrait;

    /**
     * The enum data type
     */
    const TYPE = 'serverenum';

    /**
     * The enum allowed values
     *
     * @return array
     */
    public static function values()
    {
        return [
            self::TYPE_RSYNC,
            self::TYPE_EB,
            self::TYPE_S3,
            self::TYPE_CD,
        ];
    }
}
