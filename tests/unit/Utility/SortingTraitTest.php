<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use PHPUnit\Framework\TestCase;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Target;

class SortingTraitTest extends TestCase
{
    public function testTargetOrder()
    {
        $d = new SortingTraitDummy;

        list($before, $after) = $this->targetData();

        $actual = $before;
        usort($actual, $d->targetSorter());

        $this->assertSame($after, $actual);
    }

    public function testTargetWithNamesOrder()
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

    public function targetData()
    {
        $a = new Target('s3');
        $b = new Target('cd');
        $c = new Target('eb');
        $d = new Target('script');
        $e = new Target('rsync');

        return [
            [
                $a,
                $b,
                $c,
                $d,
                $e,
            ],
            [
                $b,
                $c,
                $e,
                $a,
                $d,
            ]
        ];
    }

    public function targetWithNamesData()
    {
        $a = new Target('s3');
        $b = new Target('cd');
        $c = (new Target('eb'))->withName('aaa');
        $d = (new Target('script'))->withName('abc');
        $e = new Target('rsync');

        return [
            [
                $a,
                $b,
                $c,
                $d,
                $e,
            ],
            [
                $c,
                $d,
                $b,
                $e,
                $a,
            ]
        ];
    }

    public function environmentData()
    {
        $a = (new Environment)->withName('staging');
        $b = (new Environment)->withName('dev');
        $c = (new Environment)->withName('production');
        $d = (new Environment)->withName('prod');
        $e = (new Environment)->withName('beta');
        $f = (new Environment)->withName('test');

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
                $a,
                $f,
                $e,
                $d,
                $c,
            ]
        ];
    }
}

class SortingTraitDummy
{
    use SortingTrait;
}
