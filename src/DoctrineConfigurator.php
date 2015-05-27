<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use QL\Hal\Core\Type\CompressedSerializedBlobType;
use QL\Hal\Core\Type\HttpUrlType;
use QL\Hal\Core\Type\TimePointType;
use QL\Hal\Core\Type\EnumType\BuildStatusEnum;
use QL\Hal\Core\Type\EnumType\EventEnum;
use QL\Hal\Core\Type\EnumType\EventStatusEnum;
use QL\Hal\Core\Type\EnumType\PushStatusEnum;
use QL\Hal\Core\Type\EnumType\ServerEnum;

class DoctrineConfigurator
{
    /**
     * @param EntityManagerInterface $em
     */
    public function configure(EntityManagerInterface $em)
    {
        $mapping = [
            CompressedSerializedBlobType::TYPE  => CompressedSerializedBlobType::CLASS,
            HttpUrlType::TYPE                   => HttpUrlType::CLASS,
            TimePointType::TYPE                 => TimePointType::CLASS,

            ServerEnum::TYPE                => ServerEnum::CLASS,
            BuildStatusEnum::TYPE           => BuildStatusEnum::CLASS,
            PushStatusEnum::TYPE            => PushStatusEnum::CLASS,
            EventEnum::TYPE                 => EventEnum::CLASS,
            EventStatusEnum::TYPE           => EventStatusEnum::CLASS,
        ];

        $platform = $em->getConnection()->getDatabasePlatform();

        foreach ($mapping as $type => $fullyQualified) {
            Type::addType($type, $fullyQualified);

            // Register with platform
            $platform->registerDoctrineTypeMapping(sprintf('db_%s', $type), $type);
        }
    }
}
