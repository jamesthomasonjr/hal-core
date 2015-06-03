<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Utility;

use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Environment;
use QL\Hal\Core\Entity\Group;

trait SortingTrait
{
    private $sortingHelperEnvironmentOrder = [
        'dev' => 0,
        'test' => 1,
        'beta' => 2,
        'prod' => 3
    ];

    /**
     * @return callable
     */
    public function environmentSorter()
    {
        $order = $this->sortingHelperEnvironmentOrder;

        return function(Environment $a, Environment $b) use ($order) {

            $aName = strtolower($a->name());
            $bName = strtolower($b->name());

            $aOrder = isset($order[$aName]) ? $order[$aName] : 999;
            $bOrder = isset($order[$bName]) ? $order[$bName] : 999;

            if ($aOrder === $bOrder) {
                return 0;
            }

            return ($aOrder > $bOrder);
        };
    }

    /**
     * @return Closure
     */
    private function applicationSorter()
    {
        return function(Application $a, Application $b) {
            return strcasecmp($a->name(), $b->name());
        };
    }

    /**
     * @return Closure
     */
    private function groupSorter()
    {
        return function(Group $a, Group $b) {
            return strcasecmp($a->name(), $b->name());
        };
    }
}
