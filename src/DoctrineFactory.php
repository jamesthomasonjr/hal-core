<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core;

use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;

class DoctrineFactory
{
    /**
     * @return string
     */
    public static function path()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Entity';
    }

    /**
     * @return string
     */
    public static function yamlPath()
    {
        return __DIR__ . '/../config/doctrine';
    }

    /**
     * @return string
     */
    public static function doctrineProxyPath($dir = null)
    {
        $dir = $dir ?: '.doctrine';

        return __DIR__ . '/../' . trim($dir, '/');
    }

    /**
     * @param string $configDir
     *
     * @return MappingDriver
     */
    public static function buildConfigurationDriver($configDir)
    {
        $driver = new SimplifiedYamlDriver([
            $configDir => 'QL\Hal\Core\Entity'
        ]);

        $driver->setGlobalBasename('global');

        return $driver;
    }
}
