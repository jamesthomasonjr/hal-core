<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

class VCSProviderEnum
{
    use EnumTrait;

    const ERR_INVALID = '"%s" is not a valid vcs provider option.';

    const TYPE_GIT = 'git';
    const TYPE_GITHUB = 'gh';
    const TYPE_GITHUB_ENTERPRISE = 'ghe';

    /**
     * @return string
     */
    public static function defaultOption()
    {
        return self::TYPE_GITHUB_ENTERPRISE;
    }

    /**
     * @return string[]
     */
    public static function options()
    {
        return [
            self::TYPE_GIT,
            self::TYPE_GITHUB,
            self::TYPE_GITHUB_ENTERPRISE,
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
            case self::TYPE_GIT:
                return 'Git';

            case self::TYPE_GITHUB:
                return 'GitHub.com';

            case self::TYPE_GITHUB_ENTERPRISE:
                return 'GitHub Ent.';

            default:
                return 'Unknown';
        }
    }
}
