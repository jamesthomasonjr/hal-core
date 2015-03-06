<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Type;

use Doctrine\DBAL\Types\Type as BaseType;

/**
 * Push Status Enum
 */
class PushStatusEnumType extends BaseType
{
    use EnumTypeTrait;

    /**
     * The enum data type
     */
    const TYPE = "pushstatusenum";

    /**
     * The enum allowed values
     *
     * @return array
     */
    public static function values()
    {
        return [
            'Waiting',
            'Pushing',
            'Error',
            'Success'
        ];
    }
}
