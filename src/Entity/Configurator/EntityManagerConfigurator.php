<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Configurator;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use QL\Hal\Core\Entity\Type\BuildStatusEnumType;
use QL\Hal\Core\Entity\Type\CompressedSerializedBlobType;
use QL\Hal\Core\Entity\Type\DeploymentEnumType;
use QL\Hal\Core\Entity\Type\EventEnumType;
use QL\Hal\Core\Entity\Type\EventStatusEnumType;
use QL\Hal\Core\Entity\Type\HttpUrlType;
use QL\Hal\Core\Entity\Type\PushStatusEnumType;
use QL\Hal\Core\Entity\Type\TimePointType;

/**
 * Perform runtime configuration of the Doctrine Entity Manager
 */
class EntityManagerConfigurator
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
            HttpUrlType::TYPE                   => 'QL\Hal\Core\Entity\Type\HttpUrlType',
            TimePointType::TYPE                 => 'QL\Hal\Core\Entity\Type\TimePointType',
            BuildStatusEnumType::TYPE           => 'QL\Hal\Core\Entity\Type\BuildStatusEnumType',
            PushStatusEnumType::TYPE            => 'QL\Hal\Core\Entity\Type\PushStatusEnumType',
            EventEnumType::TYPE                 => 'QL\Hal\Core\Entity\Type\EventEnumType',
            EventStatusEnumType::TYPE           => 'QL\Hal\Core\Entity\Type\EventStatusEnumType',
            DeploymentEnumType::TYPE            => 'QL\Hal\Core\Entity\Type\DeploymentEnumType',
            CompressedSerializedBlobType::TYPE  => 'QL\Hal\Core\Entity\Type\CompressedSerializedBlobType',
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
