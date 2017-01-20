<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Type;

trait EnumTrait
{
    /**
     * Is the provided value a valid entry for this enum?
     *
     * @param mixed $option
     *
     * @return bool
     */
    public static function isValid($option)
    {
        return in_array($option, static::options(), true);
    }

    /**
     * Ensure the provided value is a valid type. Throw an exception if not.
     *
     * @param mixed $option
     *
     * @throws EnumException
     *
     * @return string
     */
    public static function ensureValid($option)
    {
        $option = strtolower($option);

        if (!self::isValid($option)) {
            throw new EnumException(sprintf(static::ERR_INVALID, $option));
        }

        return $option;
    }
}
