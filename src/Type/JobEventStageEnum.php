<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

class JobEventStageEnum
{
    use EnumTrait;

    const ERR_INVALID = '"%s" is not a valid event stage option.';

    const TYPE_UNKNOWN = 'unknown';

    const TYPE_BUILD_CREATE = 'build.created';
    const TYPE_BUILD_START = 'build.start';
    const TYPE_BUILD_BUILD = 'build.building';

    const TYPE_BUILD_END = 'build.end';
    const TYPE_BUILD_SUCCESS = 'build.success';
    const TYPE_BUILD_FAILURE = 'build.failure';

    const TYPE_RELEASE_CREATE = 'release.created';
    const TYPE_RELEASE_START = 'release.start';
    const TYPE_RELEASE_DEPLOY = 'release.deploying';

    const TYPE_RELEASE_END = 'release.end';
    const TYPE_RELEASE_SUCCESS = 'release.success';
    const TYPE_RELEASE_FAILURE = 'release.failure';

    /**
     * @return string
     */
    public static function defaultOption()
    {
        return self::TYPE_UNKNOWN;
    }

    /**
     * @return string[]
     */
    public static function options()
    {
        return [
            self::TYPE_UNKNOWN,

            self::TYPE_BUILD_CREATE,
            self::TYPE_BUILD_START,
            self::TYPE_BUILD_BUILD,
            self::TYPE_BUILD_END,
            self::TYPE_BUILD_SUCCESS,
            self::TYPE_BUILD_FAILURE,

            self::TYPE_RELEASE_CREATE,
            self::TYPE_RELEASE_START,
            self::TYPE_RELEASE_DEPLOY,
            self::TYPE_RELEASE_END,
            self::TYPE_RELEASE_SUCCESS,
            self::TYPE_RELEASE_FAILURE,
        ];
    }
}
