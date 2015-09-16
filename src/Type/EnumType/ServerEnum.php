<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Type\EnumType;

use Doctrine\DBAL\Types\Type as BaseType;

class ServerEnum extends BaseType
{
    const TYPE_RSYNC = 'rsync';
    const TYPE_EB = 'eb';
    const TYPE_EC2 = 'ec2';
    const TYPE_S3 = 's3';
    const TYPE_CD = 'cd';

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
            self::TYPE_S3,
            self::TYPE_CD,
        ];
    }
}
