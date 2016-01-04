<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Utility;

use PHPUnit_Framework_TestCase;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Environment;
use QL\Hal\Core\Entity\Server;

class SortingTraitTest extends PHPUnit_Framework_TestCase
{
    public function testServerNameOrder()
    {
        $d = new SortingTraitDummy;

        list($before, $after) = $this->serverNameData();

        $actual = $before;
        usort($actual, $d->serverNameSorter());

        $this->assertSame($after, $actual);
    }

    public function testServerOrder()
    {
        $d = new SortingTraitDummy;

        list($before, $after) = $this->serverData();

        $actual = $before;
        usort($actual, $d->serverSorter());

        $this->assertSame($after, $actual);
    }

    public function testDeploymentOrder()
    {
        $d = new SortingTraitDummy;

        list($before, $after) = $this->deploymentData();

        $actual = $before;
        usort($actual, $d->deploymentSorter());

        $this->assertSame($after, $actual);
    }

    public function testDeploymentWithNamesOrder()
    {
        $d = new SortingTraitDummy;

        list($before, $after) = $this->deploymentWithNamesData();

        $actual = $before;
        usort($actual, $d->deploymentSorter());

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

    public function serverNameData()
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

    public function serverData()
    {
        $a = (new Server)->withId('3')->withType('rsync')->withName('localhost:340');
        $b = (new Server)->withId('1')->withType('rsync')->withName('localhost');
        $c = (new Server)->withId('2')->withType('rsync')->withName('localhost');
        $d = (new Server)->withId('4')->withType('rsync')->withName('localhost:240');

        $e = (new Server)->withId('ec2_1')->withType('ec2');
        $f = (new Server)->withId('eb1')->withType('elasticbeanstalk');
        $g = (new Server)->withId('ec2_2')->withType('ec2');

        return [
            [
                $a,
                $b,
                $c,
                $d,
                $e,
                $f,
                $g,
            ],
            [
                $b,
                $c,
                $d,
                $a,
                $g,
                $e,
                $f,
            ]
        ];
    }

    public function deploymentData()
    {
        $serverA = (new Server)->withId('1')->withName('a');
        $serverB = (new Server)->withId('2')->withName('b');

        $a = (new Deployment)->withId('d1')->withPath('/same')->withServer($serverA);
        $b = (new Deployment)->withId('d2')->withPath('/same')->withServer($serverA);
        $c = (new Deployment)->withId('d3')->withPath('/herp')->withServer($serverB);
        $d = (new Deployment)->withId('d4')->withPath('/derp')->withServer($serverB);

        return [
            [
                $a,
                $b,
                $c,
                $d,
            ],
            [
                $a,
                $b,
                $d,
                $c,
            ]
        ];
    }

    public function deploymentWithNamesData()
    {
        $serverA = (new Server)->withId('1')->withName('d');
        $serverB = (new Server)->withId('2')->withName('a');

        $a = (new Deployment)->withId('d1')->withPath('/same')->withName('aaa')->withServer($serverA);
        $b = (new Deployment)->withId('d2')->withPath('/same')->withServer($serverA);
        $c = (new Deployment)->withId('d3')->withPath('/herp')->withName('abc')->withServer($serverB);
        $d = (new Deployment)->withId('d4')->withPath('/derp')->withServer($serverB);

        return [
            [
                $a,
                $b,
                $c,
                $d,
            ],
            [
                $d,
                $a,
                $c,
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
