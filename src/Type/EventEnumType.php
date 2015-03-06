<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Type;

use Doctrine\DBAL\Types\Type as BaseType;

/**
 * Event Type Enum
 */
class EventEnumType extends BaseType
{
    use EnumTypeTrait;

    /**
     * The enum data type
     */
    const TYPE = 'eventenum';

    /**
     * The enum allowed values
     *
     * @return array
     */
    public static function values()
    {
        return [
            'build.created',
            'build.start',
            'build.building',
            'build.end',
            'build.success',
            'build.failure',

            'push.created',
            'push.start',
            'push.pushing',
            'push.end',
            'push.success',
            'push.failure',
            'unknown'
        ];
    }
}
