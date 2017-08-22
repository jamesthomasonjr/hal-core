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
    private $typeClasses;

    /**
     * @param array $typeClasses
     */
    public function __construct(array $typeClasses = [])
    {
        $this->typeClasses = $typeClasses;
    }

    /**
     * @param EntityManagerInterface $em
     *
     * @return void
     */
    public function configure(EntityManagerInterface $em)
    {
        $platform = $em->getConnection()->getDatabasePlatform();

        foreach ($this->typeClasses as $fqcn) {

            $name = constant("${fqcn}::NAME");

            if (Type::hasType($name, $fqcn)) {
                continue;
            }

            Type::addType($name, $fqcn);

            // Register with platform
            $platform->registerDoctrineTypeMapping(sprintf('db_%s', $name), $name);
        }
    }
}
