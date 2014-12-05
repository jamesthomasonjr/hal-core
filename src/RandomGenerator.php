<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
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
