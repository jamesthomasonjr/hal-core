<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Group;
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
        $app = (new Application('ab', 'app1', 'app name'))
            ->withOrganization($org);

        $env1 = new Environment('12', 'test');
        $env2 = new Environment('34', 'beta');

        $group1 = (new Group('7'))
            ->withType('rsync')
            ->withEnvironment($env1);
        $group2 = (new Group('8'))
            ->withType('eb')
            ->withEnvironment($env2);

        $target1 = (new Target('1234'))
            ->withApplication($app)
            ->withGroup($group1);
        $target2 = (new Target('5678'))
            ->withApplication($app)
            ->withGroup($group1);

        $em->persist($org);
        $em->persist($app);
        $em->persist($env1);
        $em->persist($env2);
        $em->persist($group1);
        $em->persist($group2);

        $em->persist($target1);
        $em->persist($target2);
        $em->flush();

        $environments = $repo->getBuildableEnvironmentsByApplication($app);

        $this->assertSame([$env1], $environments);
    }

    public function testGetSortedEnvironments()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Environment::class);

        $env1 = new Environment('12', 'alpha');
        $env2 = new Environment('34', 'prod');
        $env3 = new Environment('56', 'test');
        $env4 = new Environment('78', 'beta');

        $em->persist($env1);
        $em->persist($env2);
        $em->persist($env3);
        $em->persist($env4);

        $em->flush();

        $environments = $repo->getAllEnvironmentsSorted();

        $this->assertSame([$env3, $env4, $env2, $env1], $environments);
    }
}
