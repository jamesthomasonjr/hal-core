<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core;

/**
 * Generate a unique id.
 */
class RandomGenerator
{
    public function __invoke()
    {
        return sha1(microtime(true) . mt_rand(10000,90000));
    }
}
