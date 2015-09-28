<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Repository;

use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Environment;
use QL\Hal\Core\Entity\Server;
use QL\Hal\Core\Testing\DoctrineTest;

class EnvironmentRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Environment::CLASS);

        $this->assertSame(EnvironmentRepository::CLASS, get_class($repo));
        $this->assertSame(Environment::CLASS, $repo->getClassName());
    }

    public function testGetBuildableEnvironments()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Environment::CLASS);

        $application = (new Application)
            ->withId(12)
            ->withKey('app1')
            ->withName('app name');

        $env1 = (new Environment)
            ->withId(12)
            ->withName('test');
        $env2 = (new Environment)
            ->withId(34)
            ->withName('beta');

        $server1 = (new Server)
            ->withId(12)
            ->withType('rsync')
            ->withEnvironment($env1);
        $server2 = (new Server)
            ->withId(34)
            ->withType('eb')
            ->withEnvironment($env2);

        $deployment1 = (new Deployment)
            ->withId(1234)
            ->withApplication($application)
            ->withServer($server1);
        $deployment2 = (new Deployment)
            ->withId(5678)
            ->withApplication($application)
            ->withServer($server1);

        $em->persist($application);
        $em->persist($env1);
        $em->persist($env2);
        $em->persist($server1);
        $em->persist($server2);

        $em->persist($deployment1);
        $em->persist($deployment2);
        $em->flush();

        $environments = $repo->getBuildableEnvironmentsByApplication($application);

        $this->assertSame([$env1], $environments);
    }

    public function testGetSortedEnvironments()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Environment::CLASS);

        $env1 = (new Environment)
            ->withId(34)
            ->withName('alpha');
        $env2 = (new Environment)
            ->withId(34)
            ->withName('prod');
        $env3 = (new Environment)
            ->withId(12)
            ->withName('test');
        $env4 = (new Environment)
            ->withId(34)
            ->withName('beta');

        $em->persist($env1);
        $em->persist($env2);
        $em->persist($env3);
        $em->persist($env4);

        $em->flush();

        $environments = $repo->getAllEnvironmentsSorted();

        $this->assertSame([$env3, $env4, $env2, $env1], $environments);
    }
}
