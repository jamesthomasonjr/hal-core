<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Utility;

use QL\Kraken\Core\Entity\Environment;
use QL\Kraken\Core\Entity\Property;
use QL\Kraken\Core\Entity\Target;

trait SortingTrait
{
    private $sortingHelperEnvironmentOrder = [
        'dev' => 0,
        'test' => 1,
        'beta' => 2,
        'prod' => 3,

        'dev-aws' => 4,
        'test-aws' => 5,
        'beta-aws' => 6,
        'prod-aws' => 7,

        // not used?
        'devaws' => 8,
        'testaws' => 9,
        'betaaws' => 10,
        'prodaws' => 11
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
     * @return callable
     */
    public function targetSorter()
    {
        $order = $this->sortingHelperEnvironmentOrder;

        return function(Target $a, Target $b) use ($order) {

            $aName = strtolower($a->environment()->name());
            $bName = strtolower($b->environment()->name());

            $aOrder = isset($order[$aName]) ? $order[$aName] : 999;
            $bOrder = isset($order[$bName]) ? $order[$bName] : 999;

            if ($aOrder === $bOrder) {
                return 0;
            }

            return ($aOrder > $bOrder) ? 1 : -1;
        };
    }

    /**
     * @return callable
     */
    public function sorterPropertyByEnvironment()
    {
        $order = $this->sortingHelperEnvironmentOrder;

        return function(Property $a, Property $b) use ($order) {

            $aName = strtolower($a->environment()->name());
            $bName = strtolower($b->environment()->name());

            $aOrder = isset($order[$aName]) ? $order[$aName] : 999;
            $bOrder = isset($order[$bName]) ? $order[$bName] : 999;

            if ($aOrder === $bOrder) {
                return 0;
            }

            return ($aOrder > $bOrder) ? 1 : -1;
        };
    }
}
