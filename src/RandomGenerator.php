<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core;

use MCP\DataType\GUID;

/**
 * Generate a unique id.
 */
class RandomGenerator
{
    public function __invoke()
    {
        $id = GUID::create()->asHex();
        return strtolower($id);
    }
}
