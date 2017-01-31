<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use QL\MCP\Common\GUID;

trait EntityIDTrait
{
    /**
     * @return string
     */
    public function generateEntityID()
    {
        return GUID::create()->format(GUID::STANDARD);
    }
}
