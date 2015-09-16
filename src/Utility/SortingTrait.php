<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Utility;

use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Environment;
use QL\Hal\Core\Entity\Group;
use QL\Hal\Core\Type\EnumType\ServerEnum;

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
    public function deploymentSorter()
    {
        $serverSorter = $this->serverSorter();

        return function(Deployment $a, Deployment $b) use ($serverSorter) {
            $serverA = $a->server();
            $serverB = $b->server();

            $nameA = $a->name();
            $nameB = $b->name();

            // If at least one of the deployments are named, used name comparison
            if ($nameA || $nameB) {
                $nameA = $nameA ?: $serverA->name();
                $nameB = $nameB ?: $serverB->name();
                return strcasecmp($nameA, $nameB);
            }

            // same server
            // there's a reason this doesn't compare obj to obj, but I dont remember why
            if ($serverA->id() === $serverB->id()) {
                return strcasecmp($a->path(), $b->path());
            }

            return $serverSorter($serverA, $serverB);
        };
    }

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

            return ($aOrder > $bOrder) ? 1 : -1;
        };
    }

    /**
     * @return callable
     */
    public function serverSorter()
    {
        $serverNameSorter = $this->serverNameSorter();

        return function($a, $b) use ($serverNameSorter) {
            $serverA = $a->name();
            $serverB = $b->name();

            $serverA = strtok($serverA, ':');
            $portA = strtok(':');

            $serverB = strtok($serverB, ':');
            $portB = strtok(':');

            // same server
            if ($a->id() === $b->id()) {
                return 0;
            }

            if ($a->type() !== $b->type()) {
                // put rsync at top
                if ($a->type() === ServerEnum::TYPE_RSYNC) {
                    return -1;
                } elseif ($b->type() === ServerEnum::TYPE_RSYNC) {
                    return 1;
                }

                if ($a->type() === ServerEnum::TYPE_CD) {
                    return -1;
                } elseif ($b->type() === ServerEnum::TYPE_CD) {
                    return 1;
                }

                if ($a->type() === ServerEnum::TYPE_EB) {
                    return -1;
                } elseif ($b->type() === ServerEnum::TYPE_EB) {
                    return 1;
                }

                if ($a->type() === ServerEnum::TYPE_EC2) {
                    return -1;
                } elseif ($b->type() === ServerEnum::TYPE_EC2) {
                    return 1;
                }

                if ($a->type() === ServerEnum::TYPE_S3) {
                    return 1;
                }
            }

            // Same servername, different port
            if ($serverA === $serverB) {
                if ($portA === false || $portB === false) {
                    return ($portA === false) ? -1 : 1;
                } else {
                    return ($portA < $portB) ? -1 : 1;
                }
            }

            return $serverNameSorter($serverA, $serverB);
        };
    }

    /**
     * Example:
     *
     * Internal:
     * ql1jobrnr1           ql  1   jobrnr    1
     * ql1halagent1         ql  1   halagent  1
     * ql2appbeta2          ql  2   appbeta   2
     *
     * Web tier:
     * test1appwww3         test     1  appwww   3
     * staging4app1         staging  4  app      1
     * prod3utility1        prod     3  utility  1
     *
     * @return callable
     */
    public function serverNameSorter()
    {
        $regex = '#' .
            '([a-z]{1,8})' . // Some letters. Maybe be environment, or "ql" for internal servers.
            '([\d]{1,2})' . // Digits. This is the datacenter identifier.
            '([a-z]{1,12})' . // Some letters. This usually identifies the tier or network
            '([\d]{1,2})' . // 1-2 digits. Server number.
            '([a-z]*)' . // random letters, because thats apparently a thing now.
            '#';

        return function($a, $b) use ($regex) {
            $isA = preg_match($regex, $a, $matchesA);
            $isB = preg_match($regex, $b, $matchesB);

            // One does not follow schema, move to bottom
            if (!$isA && !$isB) {
                return strcasecmp($a, $b);
            } elseif ($isA xor $isB) {
                return ($isA) ? -1 : 1;
            }

            // both match
            $parsedA = [
                'prefix' => $matchesA[1],
                'datacenter' => $matchesA[2],
                'tier' => $matchesA[3],
                'server' => $matchesA[4],
                'suffix' => $matchesA[5]
            ];

            $parsedB = [
                'prefix' => $matchesB[1],
                'datacenter' => $matchesB[2],
                'tier' => $matchesB[3],
                'server' => $matchesB[4],
                'suffix' => $matchesA[5]
            ];

            $result = $this->compareValidServername($parsedA, $parsedB);
            if ($result !== null) {
                return $result;
            }

            // fall back to just straight comparison
            return strcasecmp($a, $b);
        };
    }

    /**
     * @return Closure
     */
    public function applicationSorter()
    {
        return function(Application $a, Application $b) {
            return strcasecmp($a->name(), $b->name());
        };
    }

    /**
     * @return Closure
     */
    public function groupSorter()
    {
        return function(Group $a, Group $b) {
            return strcasecmp($a->name(), $b->name());
        };
    }

    /**
     * @param array $a
     * @param array $b
     *
     * @return int
     */
    private function compareValidServername($a, $b)
    {
        if ($a['prefix'] !== $b['prefix']) {
            // internal servers go to the top
            if ($a['prefix'] === 'ql' || $b['prefix'] === 'ql') {
                return ($a['prefix'] === 'ql') ? 1 : -1;
            }

            $order = $this->sortingHelperEnvironmentOrder;
            $envA = $a['prefix'];
            $envB = $b['prefix'];

            $aOrder = isset($order[$envA]) ? $order[$envA] : 999;
            $bOrder = isset($order[$envB]) ? $order[$envB] : 999;

            return ($aOrder > $bOrder) ? 1 : -1;

            return strcmp($a['datacenter'], $b['datacenter']);
        }

        // same datacenter, tier different, compare tier
        if ($a['tier'] !== $b['tier']) {
            return strcmp($a['tier'], $b['tier']);
        }

        // datacenters different, compare datacenter
        if ($a['datacenter'] !== $b['datacenter']) {
            return strcmp($a['datacenter'], $b['datacenter']);
        }
        // same datacenter, same tier, compare server
        if ($a['server'] !== $b['server']) {
            return ($a['server'] > $b['server']) ? 1 : -1;
        }


        // same datacenter, same tier, same server, compare bullshit letters at the very end
        if ($a['suffix'] !== $b['suffix']) {
            return strcmp($a['suffix'], $b['suffix']);
        }

        return null;
    }
}
