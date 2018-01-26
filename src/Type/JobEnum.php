<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

class JobEnum
{
    use EnumTrait;

    const ERR_INVALID = '"%s" is not a valid job option.';

    const TYPE_JOB = 'job';
    const TYPE_BUILD = 'build';
    const TYPE_RELEASE = 'release';

    /**
     * @return string
     */
    public static function defaultOption()
    {
        return self::TYPE_BUILD;
    }

    /**
     * @return string[]
     */
    public static function options()
    {
        return [
            self::TYPE_BUILD,
            self::TYPE_RELEASE,
        ];
    }
}
