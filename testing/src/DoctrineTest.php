<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Testing;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use PHPUnit_Framework_TestCase;
use QL\Hal\Core\Utility\DoctrineConfigurator;
use QL\Hal\Core\Utility\DoctrineCustomTypes;
use QL\Hal\Core\Utility\DoctrineFactory;
use QL\Kraken\Core\Utility\DoctrineCustomTypes as KrakenDoctrineCustomTypes;

class DoctrineTest extends PHPUnit_Framework_TestCase
{
    private static $typesSet;

    public function getEntityManager()
    {
        $driver = new SimplifiedYamlDriver([
            DoctrineFactory::halYaml() => 'QL\Hal\Core\Entity',
            DoctrineFactory::krakenYaml() => 'QL\Kraken\Core\Entity',
        ]);
        $driver->setGlobalBasename('global');

        $config = Setup::createConfiguration(true);
        $config->setMetadataDriverImpl($driver);

        $configurator = new DoctrineConfigurator;
        $configurator->addEntityMappings(DoctrineCustomTypes::getMapping());
        $configurator->addEntityMappings(KrakenDoctrineCustomTypes::getMapping());

        $em = EntityManager::create([
            'driver' => 'pdo_sqlite',
            'memory' => true
        ], $config);

        if (!self::$typesSet) {
            $configurator->configure($em);
            self::$typesSet = true;
        }

        $this->prepareDatabase($em);
        return $em;
    }

    private function prepareDatabase($em)
    {
        $metadatas = $em->getMetadataFactory()->getAllMetadata();

        $tool = new SchemaTool($em);
        $tool->createSchema($metadatas);
    }

}
