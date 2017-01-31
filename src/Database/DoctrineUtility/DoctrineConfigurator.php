<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Database\DoctrineUtility;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineConfigurator
{
    /**
     * @var array
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
