<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Type;

use Doctrine\DBAL\Types\Type as BaseType;

/**
 * Deployment Type Enum
 */
class DeploymentEnumType extends BaseType
{
    use EnumTypeTrait;

    /**
     * The enum data type
     */
    const TYPE = 'deploymentenum';

    /**
     * The enum allowed values
     *
     * @return array
     */
    public static function values()
    {
        return [
            'rsync',
            'elasticbeanstalk'
        ];
    }
}
