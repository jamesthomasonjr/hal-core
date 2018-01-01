<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Organization;
use Hal\Core\Entity\Target;
use Hal\Core\Testing\DoctrineTest;

class EnvironmentRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Environment::class);

        $this->assertSame(EnvironmentRepository::class, get_class($repo));
        $this->assertSame(Environment::class, $repo->getClassName());
    }

    public function testGetBuildableEnvironments()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Environment::class);

        $org = new Organization;
        $app = (new Application)
            ->withName('app name')
            ->withOrganization($org);

        $env1 = (new Environment)->withName('test');
        $env2 = (new Environment)->withName('beta');
        $env3 = (new Environment)->withName('prod');

        $target1 = (new Target)
            ->withApplication($app)
            ->withEnvironment($env1);
        $target2 = (new Target)
            ->withApplication($app);

        $this->persist($em, [$org, $app, $env1, $env2, $env3, $target1, $target2]);

        $environments = $repo->getBuildableEnvironmentsByApplication($app);

        $this->assertSame([$env1], $environments);
    }

    public function testGetSortedEnvironments()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Environment::class);

        $env1 = (new Environment)->withName('alpha');
        $env2 = (new Environment)->withName('prod');
        $env3 = (new Environment)->withName('test');
        $env4 = (new Environment)->withName('beta');

        $this->persist($em, [$env1, $env2, $env3, $env4]);

        $environments = $repo->getAllEnvironmentsSorted();

        $this->assertSame([$env3, $env4, $env2, $env1], $environments);
    }
}
