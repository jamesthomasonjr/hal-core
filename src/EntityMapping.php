<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core;

/**
 * This class exists for no other reason than to find the file path of the
 * domain entities for Doctrine mapping.
 *
 * This allows other consumers of this package to use doctrine mappings without
 * knowing the exact file path to that namespace.
 */
class EntityMapping
{
    /**
     * @return string
     */
    public static function path()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Entity';
    }
}
