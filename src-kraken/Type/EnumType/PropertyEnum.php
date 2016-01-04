<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Type\EnumType;

use Doctrine\DBAL\Types\Type as BaseType;
use QL\Hal\Core\Type\EnumType\EnumTypeTrait;

class PropertyEnum extends BaseType
{
    const TYPE_STRING = 'string';
    const TYPE_STRINGS = 'strings';
    const TYPE_FLAG = 'bool';
    const TYPE_INT = 'int';
    const TYPE_FLOAT = 'float';

    use EnumTypeTrait;

    /**
     * The enum data type
     */
    const TYPE = 'propertyenum';

    /**
     * The enum allowed values
     *
     * @return array
     */
    public static function values()
    {
        return [
            self::TYPE_STRING,
            self::TYPE_STRINGS,
            self::TYPE_FLAG,
            self::TYPE_INT,
            self::TYPE_FLOAT
        ];
    }

    /**
     * The enum allowed values
     *
     * @return array
     */
    public static function map()
    {
        return [
            self::TYPE_STRING => 'Text',
            self::TYPE_STRINGS => 'List (text)',
            self::TYPE_FLAG => 'Flag',
            self::TYPE_INT => 'Number (integer)',
            self::TYPE_FLOAT => 'Number (decimal)'
        ];
    }
}
