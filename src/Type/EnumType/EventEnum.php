<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Type\EnumType;

use Doctrine\DBAL\Types\Type as BaseType;

class EventEnum extends BaseType
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
