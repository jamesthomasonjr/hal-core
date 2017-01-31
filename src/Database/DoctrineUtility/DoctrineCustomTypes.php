<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Database\DoctrineUtility;

use Hal\Core\Type\CompressedJSONArrayType;
use Hal\Core\Type\TimePointType;

/**
 * Try to avoid using custom types as much as possible!
 */
class DoctrineCustomTypes
{
    /**
     * @return array
     */
    public static function getMapping()
    {
        return [
            CompressedJSONArrayType::NAME  => CompressedJSONArrayType::class,
            TimePointType::NAME => TimePointType::class
        ];
    }
}
