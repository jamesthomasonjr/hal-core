<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

class GroupEnum
{
    use EnumTrait;

    const ERR_INVALID = '"%s" is not a valid group option.';

    const TYPE_RSYNC = 'rsync';
    const TYPE_EB = 'eb';
    const TYPE_S3 = 's3';
    const TYPE_CD = 'cd';
    const TYPE_SCRIPT = 'script';

    /**
     * @return string
     */
    public static function defaultOption()
    {
        return self::TYPE_RSYNC;
    }

    /**
     * @return string[]
     */
    public static function options()
    {
        return [
            self::TYPE_RSYNC,
            self::TYPE_EB,
            self::TYPE_S3,
            self::TYPE_CD,
            self::TYPE_SCRIPT
        ];
    }
}
