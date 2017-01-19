<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core;

use QL\MCP\Common\GUID;

/**
 * Generate a unique id.
 */
class RandomGenerator
{
    public function __invoke()
    {
        $id = GUID::create()->format(GUID::STANDARD);

        return $id;
    }
}
