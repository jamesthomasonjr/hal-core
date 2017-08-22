<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Testing;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Hal\Core\Database\DoctrineUtility\DoctrineConfigurator;
use Hal\Core\Database\DoctrineUtility\DoctrineCustomTypes;
use Hal\Core\Database\DoctrineUtility\DoctrineFactory;
use PHPUnit\Framework\TestCase;
use Hal\Core\DI;

class DoctrineTest extends TestCase
{
    private static $testEM;

    public function getEntityManager()
    {
        $root = realpath(__DIR__ . '/../../');
        putenv('HAL_DI_DISABLE_CACHE_ON=1');
        putenv("HAL_ORM_PROXY_DIR=${root}/.doctrine");
        putenv("HAL_ORM_CONFIG_DIR=${root}/config/doctrine");

        putenv('HAL_DB_USER=dummyuser');
        putenv('HAL_DB_PASSWORD=dummyuser');
        putenv('HAL_DB_HOST=localhost');
        putenv('HAL_DB_PORT=NA');
        putenv('HAL_DB_NAME=NA');
        putenv('HAL_DB_DRIVER=NA');

        $container = DI::buildDI($root, true);
        $em = $container->get('doctrine.em.proxy');

        if (!self::$testEM) {
            self::$testEM = $em;
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
