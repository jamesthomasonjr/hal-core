<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

class JobEventStatusEnum
{
    use EnumTrait;

    const ERR_INVALID = '"%s" is not a valid status option.';

    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_FAILURE = 'failure';

    /**
     * @return string
     */
    public static function defaultOption()
    {
        return self::TYPE_INFO;
    }

    /**
     * @return string[]
     */
    public static function options()
    {
        return [
            self::TYPE_INFO,

            self::TYPE_SUCCESS,
            self::TYPE_FAILURE,
        ];
    }
}
