<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

class CredentialEnum
{
    use EnumTrait;

    const ERR_INVALID = '"%s" is not a valid credential option.';

    const TYPE_AWS = 'aws';
    const TYPE_PRIVATEKEY = 'privatekey';

    /**
     * @return string
     */
    public static function defaultOption()
    {
        return self::TYPE_AWS;
    }

    /**
     * @return string[]
     */
    public static function options()
    {
        return [
            self::TYPE_AWS,
            self::TYPE_PRIVATEKEY,
        ];
    }
}
