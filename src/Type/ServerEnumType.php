<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Type;

use Doctrine\DBAL\Types\Type as BaseType;

/**
 * Server Type Enum
 */
class ServerEnumType extends BaseType
{
    const TYPE_RSYNC = 'rsync';
    const TYPE_EB = 'elasticbeanstalk';
    const TYPE_EC2 = 'ec2';

    use EnumTypeTrait;

    /**
     * The enum data type
     */
    const TYPE = 'serverenum';

    /**
     * The enum allowed values
     *
     * @return array
     */
    public static function values()
    {
        return [
            self::TYPE_RSYNC,
            self::TYPE_EB,
            self::TYPE_EC2,
        ];
    }
}
