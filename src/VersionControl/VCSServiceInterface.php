<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\VersionControl;

use Hal\Core\Entity\Application;

interface VCSServiceInterface
{
    /**
     * List all possible source code versions from version control provider
     *
     * Can return a tree structure, or a flat array: the consumer of the array should be included with the module.
     *
     * @param Application $application
     *
     * @throws VSCException
     *
     * @return array
     */
    public function list(Application $application): array;
}
