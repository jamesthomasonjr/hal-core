<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Utility;

use QL\Hal\Core\Type\CompressedSerializedBlobType;
use QL\Hal\Core\Type\HttpUrlType;
use QL\Hal\Core\Type\TimePointType;
use QL\Hal\Core\Type\EnumType\BuildStatusEnum;
use QL\Hal\Core\Type\EnumType\CredentialEnum;
use QL\Hal\Core\Type\EnumType\EventEnum;
use QL\Hal\Core\Type\EnumType\EventStatusEnum;
use QL\Hal\Core\Type\EnumType\PushStatusEnum;
use QL\Hal\Core\Type\EnumType\ServerEnum;
use QL\Hal\Core\Type\EnumType\UserTypeEnum;

class DoctrineCustomTypes
{
    /**
     * @return array
     */
    public static function getMapping()
    {
        return [
            CompressedSerializedBlobType::TYPE  => CompressedSerializedBlobType::CLASS,
            HttpUrlType::TYPE => HttpUrlType::CLASS,
            TimePointType::TYPE => TimePointType::CLASS,

            ServerEnum::TYPE => ServerEnum::CLASS,
            BuildStatusEnum::TYPE => BuildStatusEnum::CLASS,
            PushStatusEnum::TYPE => PushStatusEnum::CLASS,
            EventEnum::TYPE => EventEnum::CLASS,
            EventStatusEnum::TYPE => EventStatusEnum::CLASS,

            UserTypeEnum::TYPE => UserTypeEnum::CLASS,

            CredentialEnum::TYPE => CredentialEnum::CLASS,
        ];
    }
}
