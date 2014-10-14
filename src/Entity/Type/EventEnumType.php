<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Type;

use Doctrine\DBAL\Types\Type as BaseType;

/**
 *  Event Doctrine Enum Type
 */
class EventEnumType extends BaseType
{
    use EnumTypeTrait;

    /**
     *  The enum data type
     */
    const TYPE = 'eventenum';

    /**
     *  The enum allowed values
     *
     *  @var array
     */
    protected $values = [
        'build.created',
        'build.start',
        'build.end',
        'build.success',
        'build.failure',

        'push.created',
        'push.start',
        'push.end',
        'push.success',
        'push.failure',
        'unknown'
    ];
}
