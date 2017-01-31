<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use PHPUnit_Framework_TestCase;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Group;
use Hal\Core\Entity\Target;

class SortingTraitTest extends PHPUnit_Framework_TestCase
{
    public function testHostnameOrder()
    {
        $d = new SortingTraitDummy;

        list($before, $after) = $this->hostnamesData();

        $actual = $before;
        usort($actual, $d->hostnameSorter());

        $this->assertSame($after, $actual);
    }

    public function testGroupOrder()
    {
        $d = new SortingTraitDummy;

        list($before, $after) = $this->groupData();

        $actual = $before;
        usort($actual, $d->groupSorter());

        $this->assertSame($after, $actual);
    }

    public function testTargetOrder()
    {
        $d = new SortingTraitDummy;

        list($before, $after) = $this->targetData();

        $actual = $before;
        usort($actual, $d->targetSorter());

        $this->assertSame($after, $actual);
    }

    public function testDeploymentWithNamesOrder()
    {
        $d = new SortingTraitDummy;

        list($before, $after) = $this->targetWithNamesData();

        $actual = $before;
        usort($actual, $d->targetSorter());

        $this->assertSame($after, $actual);
    }

    public function testEnvironmentOrder()
    {
        $d = new SortingTraitDummy;

        list($before, $after) = $this->environmentData();

        $actual = $before;
        usort($actual, $d->environmentSorter());

        $this->assertSame($after, $actual);
    }

    public function hostnamesData()
    {
        return [
            [
                'test1web1',
                'test1web2',
                'beta1web1',
                'beta2web1',
                'prod1web1',
                'prod1web2',
                'prod1web3',
                'prod1web4',
                'prod2web1',
                'prod2web2',
                'prod2web4',
                'test1web9',
                'test1web10',
                'beta1web3',
                'beta1web4',
                'prod1web7',
                'prod1web9',
                'prod1web10',
                'prod2web10',
                'prod1bravo1',
                'prod2bravo1',
                'rm1myapptest2',
                'rm3myapptest2',
                'dev1alfa1',
                'dev1alfa2',
                'dev1alfa3',
                'dev1alfa4',
                'dev2alfa1',
                'dev2alfa2',
                'dev2alfa3',
                'dev2alfa4',
                'rm1myappb2',
                'rm3myappb2',
                'rm1myapppr2',
                'rm3myapppr2',
                'test1bravo1',
                'beta1bravo1',
                'beta2bravo1',
                'ql4servicedev5',
                'ql4servicedev10',
                'ql4servicedev15',
                'ql4servicedev20',
                'ql4servicedev25',
                'rm1myapp3',
                'rm1myapp2',
                'rm1myapp1',
                'beta1web2',
                'beta2web2',
                'rm1myapptest1',
                'rm1myappb1',
                'prod1charlie1',
                'prod2charlie1',
                'test1gamma1',
                'test1gamma2',
                'test1gamma3',
                'test1gamma4',
                'ql2haltestui1',
                'ql2haltestagt1',
                'beta1charlie1',
                'beta2charlie1',
                'ql3bravopr1',
                'localhost'
            ],
            [
                'dev1alfa1',
                'dev1alfa2',
                'dev1alfa3',
                'dev1alfa4',
                'dev2alfa1',
                'dev2alfa2',
                'dev2alfa3',
                'dev2alfa4',
                'test1bravo1',
                'test1gamma1',
                'test1gamma2',
                'test1gamma3',
                'test1gamma4',
                'test1web1',
                'test1web2',
                'test1web9',
                'test1web10',
                'beta1bravo1',
                'beta2bravo1',
                'beta1charlie1',
                'beta2charlie1',
                'beta1web1',
                'beta1web2',
                'beta1web3',
                'beta1web4',
                'beta2web1',
                'beta2web2',
                'prod1bravo1',
                'prod2bravo1',
                'prod1charlie1',
                'prod2charlie1',
                'prod1web1',
                'prod1web2',
                'prod1web3',
                'prod1web4',
                'prod1web7',
                'prod1web9',
                'prod1web10',
                'prod2web1',
                'prod2web2',
                'prod2web4',
                'prod2web10',
                'rm1myapp1',
                'rm1myapp2',
                'rm1myapp3',
                'rm1myappb1',
                'rm1myappb2',
                'rm3myappb2',
                'rm1myapppr2',
                'rm3myapppr2',
                'rm1myapptest1',
                'rm1myapptest2',
                'rm3myapptest2',
                'ql3bravopr1',
                'ql2haltestagt1',
                'ql2haltestui1',
                'ql4servicedev5',
                'ql4servicedev10',
                'ql4servicedev15',
                'ql4servicedev20',
                'ql4servicedev25',
                'localhost'
            ]
        ];
    }

    public function groupData()
    {
        $a = (new Group(null, 'rsync'))->withName('localhost:340');
        $b = (new Group(null, 'rsync'))->withName('localhost');
        $c = (new Group(null, 'rsync'))->withName('localhost');
        $d = (new Group(null, 'rsync'))->withName('localhost:240');

        $f = new Group(null, 'eb');

        return [
            [
                $a,
                $b,
                $c,
                $d,
                $f,
            ],
            [
                $f,
                $c,
                $b,
                $d,
                $a,
            ]
        ];
    }

    public function targetData()
    {
        $groupA = (new Group)->withName('a');
        $groupB = (new Group)->withName('b');

        $a = (new Target)->withParameter('path', '/same2')->withGroup($groupA);
        $b = (new Target)->withParameter('path', '/same1')->withGroup($groupA);
        $c = (new Target)->withParameter('path', '/herp')->withGroup($groupB);
        $d = (new Target)->withParameter('path', '/derp')->withGroup($groupB);

        return [
            [
                $a,
                $b,
                $c,
                $d,
            ],
            [
                $d,
                $c,
                $b,
                $a,
            ]
        ];
    }

    public function targetWithNamesData()
    {
        $groupA = (new Group)->withName('d');
        $groupB = (new Group)->withName('a');

        $a = (new Target)->withParameter('path', '/same')->withGroup($groupA)->withName('aaa');
        $b = (new Target)->withParameter('path', '/same')->withGroup($groupA);
        $c = (new Target)->withParameter('path', '/herp')->withGroup($groupB)->withName('abc');
        $d = (new Target)->withParameter('path', '/derp')->withGroup($groupB);

        return [
            [
                $a,
                $b,
                $c,
                $d,
            ],
            [
                $a,
                $c,
                $d,
                $b,
            ]
        ];
    }

    public function environmentData()
    {
        $a = (new Environment)->withName('dev-aws');
        $b = (new Environment)->withName('dev');
        $c = (new Environment)->withName('prod-aws');
        $d = (new Environment)->withName('prod');
        $e = (new Environment)->withName('beta');
        $f = (new Environment)->withName('test-aws');

        return [
            [
                $a,
                $b,
                $c,
                $d,
                $e,
                $f,
            ],
            [
                $b,
                $e,
                $d,
                $a,
                $f,
                $c,
            ]
        ];
    }
}

class SortingTraitDummy
{
    use SortingTrait;
}
