<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Organization;
use PHPUnit\Framework\TestCase;

class ScopedEntityTraitTest extends TestCase
{
    public function testDefaultState()
    {
        $dummy = new ScopedEntityTraitDummy;

        $this->assertSame(null, $dummy->application());
        $this->assertSame(null, $dummy->environment());
        $this->assertSame(null, $dummy->organization());
    }

    public function testSetters()
    {
        $app = new Application;
        $env = new Environment;
        $org = new Organization;

        $dummy = new ScopedEntityTraitDummy;

        $this->assertSame(null, $dummy->application());
        $this->assertSame(null, $dummy->environment());
        $this->assertSame(null, $dummy->organization());

        $dummy
            ->withApplication($app)
            ->withEnvironment($env)
            ->withOrganization($org);

        $this->assertSame($app, $dummy->application());
        $this->assertSame($env, $dummy->environment());
        $this->assertSame($org, $dummy->organization());

        $dummy
            ->withApplication(null)
            ->withEnvironment(null)
            ->withOrganization(null);

        $this->assertSame(null, $dummy->application());
        $this->assertSame(null, $dummy->environment());
        $this->assertSame(null, $dummy->organization());
    }
}

class ScopedEntityTraitDummy
{
    use ScopedEntityTrait;

    public function __construct()
    {
        $this->initializeScopes();
    }
}
