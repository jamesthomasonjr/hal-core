<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use QL\Hal\Core\Type\BuildStatusEnumType;
use QL\Hal\Core\Type\CompressedSerializedBlobType;
use QL\Hal\Core\Type\EventEnumType;
use QL\Hal\Core\Type\EventStatusEnumType;
use QL\Hal\Core\Type\HttpUrlType;
use QL\Hal\Core\Type\PushStatusEnumType;
use QL\Hal\Core\Type\ServerEnumType;
use QL\Hal\Core\Type\TimePointType;

/**
 * Perform runtime configuration of the Doctrine Entity Manager
 */
class DoctrineConfigurator
{
    /**
     * Run the configuration
     *
     * @param EntityManagerInterface $em
     */
    public function configure(EntityManagerInterface $em)
    {
        // @todo switch to php 5.5 so we can use ClassName::class
        $mapping = [
            HttpUrlType::TYPE                   => HttpUrlType::CLASS,
            TimePointType::TYPE                 => TimePointType::CLASS,
            ServerEnumType::TYPE                => ServerEnumType::CLASS,

            BuildStatusEnumType::TYPE           => BuildStatusEnumType::CLASS,
            PushStatusEnumType::TYPE            => PushStatusEnumType::CLASS,

            EventEnumType::TYPE                 => EventEnumType::CLASS,
            EventStatusEnumType::TYPE           => EventStatusEnumType::CLASS,

            CompressedSerializedBlobType::TYPE  => CompressedSerializedBlobType::CLASS,
        ];

        foreach ($mapping as $type => $fullyQualified) {
            Type::addType($type, $fullyQualified);
        }

        $platform = $em->getConnection()->getDatabasePlatform();

        foreach ($mapping as $type => $fullyQualified) {
            $platform->registerDoctrineTypeMapping(sprintf('db_%s', $type), $type);
        }
    }
}
