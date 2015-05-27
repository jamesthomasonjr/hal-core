<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Utility;

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
     * @type array
     */
    private $mapping;

    /**
     * @param array mapping
     */
    public function __construct(array $mapping = [])
    {
        $this->mapping = $mapping;
    }

    /**
     * @param array $mapping
     *
     * @return void
     */
    public function addEntityMappings(array $mapping)
    {
        foreach ($mapping as $type => $fq) {
            $this->mapping[$type] = $fq;
        }
    }

    /**
     * @param EntityManagerInterface $em
     *
     * @return void
     */
    public function configure(EntityManagerInterface $em)
    {
        $platform = $em->getConnection()->getDatabasePlatform();

        foreach ($this->mapping as $type => $fullyQualified) {
            Type::addType($type, $fullyQualified);

            // Register with platform
            $platform->registerDoctrineTypeMapping(sprintf('db_%s', $type), $type);
        }
    }
}
