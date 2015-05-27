<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
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
