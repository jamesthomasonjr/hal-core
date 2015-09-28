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

class DeploymentTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Deployment::CLASS);

        $this->assertSame(DeploymentRepository::CLASS, get_class($repo));
        $this->assertSame(Deployment::CLASS, $repo->getClassName());
    }

    public function testGetDeploymentsByApplicationAndEnvironment()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Deployment::CLASS);

        $application = (new Application)
            ->withId(12)
            ->withKey('app1')
            ->withName('app name');
        $env = (new Environment)->withId(34);
        $server = (new Server)
            ->withId(56)
            ->withType('rsync')
            ->withEnvironment($env);

        $deployment1 = (new Deployment)
            ->withId(1234)
            ->withApplication($application)
            ->withServer($server);
        $deployment2 = (new Deployment)
            ->withId(5678)
            ->withApplication($application)
            ->withServer($server);

        $em->persist($application);
        $em->persist($env);
        $em->persist($server);

        $em->persist($deployment1);
        $em->persist($deployment2);
        $em->flush();

        $deployments = $repo->getDeploymentsByApplicationEnvironment($application, $env);

        $this->assertSame([$deployment1, $deployment2], $deployments);
    }
}
