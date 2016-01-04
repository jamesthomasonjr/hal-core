<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Utility;

use QL\Kraken\Core\Type\EnumType\PropertyEnum;

class DoctrineCustomTypes
{
    /**
     * @return array
     */
    public static function getMapping()
    {
        return [
            PropertyEnum::TYPE  => PropertyEnum::CLASS
        ];
    }
}
