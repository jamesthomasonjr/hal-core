<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

class JobStatusEnum
{
    use EnumTrait;

    const ERR_INVALID = '"%s" is not a valid status option.';

    const TYPE_PENDING = 'pending';
    const TYPE_RUNNING = 'running';
    const TYPE_DEPLOYING = 'deploying';

    const TYPE_SUCCESS = 'success';
    const TYPE_FAILURE = 'failure';
    const TYPE_REMOVED = 'removed';

    /**
     * @return string
     */
    public static function defaultOption()
    {
        return self::TYPE_PENDING;
    }

    /**
     * @return string[]
     */
    public static function options()
    {
        return [
            self::TYPE_PENDING,

            self::TYPE_RUNNING,
            self::TYPE_DEPLOYING,

            self::TYPE_SUCCESS,
            self::TYPE_FAILURE,
            self::TYPE_REMOVED,
        ];
    }
}
