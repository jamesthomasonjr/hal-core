<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Type\EnumType;

use Doctrine\DBAL\Types\Type as BaseType;

class ProcessStatusEnum extends BaseType
{
    use StringEnumTypeTrait;

    /**
     * The enum data type
     */
    const TYPE = 'processstatusenum';

    /**
     * The enum allowed values
     *
     * @return array
     */
    public static function values()
    {
        return [
            'Pending',
            'Aborted',
            'Launched'
        ];
    }
}
