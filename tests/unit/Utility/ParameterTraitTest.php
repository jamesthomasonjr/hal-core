<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use PHPUnit\Framework\TestCase;

class ParameterTraitTest extends TestCase
{
    public function testDefaultState()
    {
        $dummy = new ParameterTraitDummy;

        $this->assertSame(true, is_array($dummy->parameters()));
    }

    public function testWithSetter()
    {
        $dummy = new ParameterTraitDummy;

        $dummy->withParameter('derp', 'doop');

        $this->assertSame('doop', $dummy->parameter('derp'));
        $this->assertSame(null, $dummy->parameter('noop'));
    }

    public function testSettingAllValues()
    {
        $dummy = new ParameterTraitDummy;

        $dummy->withParameter('derp', 'doop');
        $this->assertSame('doop', $dummy->parameter('derp'));

        $dummy->withParameters([]);
        $this->assertSame(null, $dummy->parameter('derp'));

        $dummy->withParameters(['derp' => 'doop']);

        $this->assertSame('doop', $dummy->parameter('derp'));

        $this->assertSame(['derp' => 'doop'], $dummy->parameters());
    }
}

class ParameterTraitDummy
{
    use ParameterTrait;

    public function __construct()
    {
        $this->initializeParameters();
    }
}
