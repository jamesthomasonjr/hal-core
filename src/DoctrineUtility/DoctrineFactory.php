<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\DoctrineUtility;

class DoctrineFactory
{
    const DEFAULT_CONFIGURATION_PATH = 'configuration/doctrine';
    const DEFAULT_PROXY_PATH = '.doctrine';

    /**
     * @param string|null $dir
     *
     * @return string
     */
    public static function configurationPath($dir = null)
    {
        $dir = $dir ?: static::DEFAULT_CONFIGURATION_PATH;

        return static::root() . '/' . trim($dir, '/');
    }

    /**
     * @param string|null $dir
     *
     * @return string
     */
    public static function proxyPath($dir = null)
    {
        $dir = $dir ?: static::DEFAULT_PROXY_PATH;

        return static::root() . '/' . trim($dir, '/');
    }

    /**
     * @return array
     */
    public static function buildConfigurationMapping()
    {
        $mapping = [];
        foreach (func_get_args() as $arg) {
            list($dir, $namespace) = $arg;

            $mapping[$dir] = $namespace;
        }

        return $mapping;
    }

    /**
     * @return string
     */
    protected static function root()
    {
        return __DIR__ . '/../..';
    }
}
