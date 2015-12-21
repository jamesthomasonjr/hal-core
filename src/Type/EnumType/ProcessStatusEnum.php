<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
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
