<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Utility;

class DoctrineFactory
{
    /**
     * @return string
     */
    public static function halYaml()
    {
        return self::root() . '/configuration/doctrine';
    }

    /**
     * @return string
     */
    public static function krakenYaml()
    {
        return self::root() . '/configuration/kraken';
    }

    /**
     * @return string
     */
    public static function proxyPath($dir = null)
    {
        $dir = $dir ?: '.doctrine';

        return self::root() . '/' . trim($dir, '/');
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
    private static function root()
    {
        return __DIR__ . '/../..';
    }
}
