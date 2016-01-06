<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Repository;

use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Environment;
use QL\Hal\Core\Entity\Push;
use QL\Hal\Core\Testing\DoctrineTest;
use QL\MCP\Common\Time\TimePoint;

class PushRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $this->assertSame(PushRepository::CLASS, get_class($repo));
        $this->assertSame(Push::CLASS, $repo->getClassName());
    }

    public function testGetAvailableRollbacksByDeployment()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $deployment1 = new Deployment;
        $deployment2 = new Deployment;

        $pushes = $this->getMockPushes($em, $deployment1, $deployment2);

        $rollbacks = $repo->getAvailableRollbacksByDeployment($deployment1);

        $this->assertCount(1, $rollbacks);

        $actual = $this->dePaginate($rollbacks);
        $this->assertSame($pushes[2], $actual[0]);
    }

    public function testGetAllPushesByDeployment()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $deployment1 = new Deployment;
        $deployment2 = new Deployment;

        $pushes = $this->getMockPushes($em, $deployment1, $deployment2);

        $actual = $repo->getByDeployment($deployment2);

        $this->assertCount(2, $actual);

        $actual = $this->dePaginate($actual);
        $this->assertSame($pushes[4], $actual[0]);
        $this->assertSame($pushes[5], $actual[1]);
    }

    public function testGetAllPushesByApplication()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $deployment1 = new Deployment;
        $deployment2 = new Deployment;

        $pushes = $this->getMockPushes($em, $deployment1, $deployment2);

        // Pull app from first push
        $application1 = $pushes[0]->application();

        $actual = $repo->getByApplication($application1);

        $this->assertCount(4, $actual);
    }

    public function testGetAllPushesByApplicationFilteredByRef()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $deployment1 = new Deployment;
        $deployment2 = new Deployment;

        $pushes = $this->getMockPushes($em, $deployment1, $deployment2);

        // Pull app from first push
        $application1 = $pushes[0]->application();

        $actual = $repo->getByApplication($application1, 25, 0, 'master');

        $this->assertCount(1, $actual);

        $actual = $this->dePaginate($actual);
        $this->assertSame($pushes[3], $actual[0]);
    }

    public function testGetMostRecentPushByDeployment()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $deployment1 = new Deployment;
        $deployment2 = new Deployment;

        $pushes = $this->getMockPushes($em, $deployment1, $deployment2);

        $push1 = $repo->getMostRecentByDeployment($deployment1);
        $push2 = $repo->getMostRecentByDeployment($deployment2);

        $this->assertSame($push1, $pushes[1]);
        $this->assertSame($push2, $pushes[4]);
    }

    public function testGetMostRecentSuccessfulPushByDeployment()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $deployment1 = new Deployment;
        $deployment2 = new Deployment;

        $pushes = $this->getMockPushes($em, $deployment1, $deployment2);

        $push1 = $repo->getMostRecentSuccessByDeployment($deployment1);
        $push2 = $repo->getMostRecentSuccessByDeployment($deployment2);

        $this->assertSame($push1, $pushes[2]);
        $this->assertSame($push2, null);
    }

    public function testGetAllPushesByApplicationAndEnvironmentFilteredByRef()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $deployment1 = new Deployment;
        $deployment2 = new Deployment;

        $environment = (new Environment)
            ->withId(1234)
            ->withName('test');

        $pushes = $this->getMockPushes($em, $deployment1, $deployment2, $environment);

        // Pull app from first push
        $application1 = $pushes[0]->application();

        $actual = $repo->getByApplicationForEnvironment($application1, $environment, 25, 0, '1BCDE12345ABCDE12345ABCDE12345ABCDE12345');

        $this->assertCount(1, $actual);

        $actual = $this->dePaginate($actual);
        $this->assertSame($pushes[1], $actual[0]);
    }

    public function getMockPushes($em, Deployment $deployment1, Deployment $deployment2, Environment $environment = null)
    {
        $application1 = (new Application)
            ->withId(12)
            ->withKey('app1')
            ->withName('App Name');
        $application2 = (new Application)
            ->withId(34)
            ->withKey('app2')
            ->withName('App Name');

        $build1 = (new Build('b1.abcd'))
            ->withStatus('Success')
            ->withBranch('pull/1234');
        $build2 = (new Build('b2.abcd'))
            ->withStatus('Removed')
            ->withBranch('master');
        $build3 = (new Build('b3.abcd'))
            ->withStatus('Success')
            ->withBranch('derpbranch')
            ->withCommit('1bcde12345abcde12345abcde12345abcde12345');

        $environment2 = (new Environment)
            ->withId(5678)
            ->withName('beta');

        if (!$environment) {
            $environment = (new Environment)
                ->withId(1234)
                ->withName('test');
        }

        $build1->withEnvironment($environment);
        $build2->withEnvironment($environment2);
        $build3->withEnvironment($environment);

        // app1
        $push1 = (new Push('p1.abcd'))
            ->withApplication($application1)
            ->withDeployment($deployment1)
            ->withBuild($build1)
            ->withCreated(new TimePoint(2015, 8, 09, 12, 0, 0, 'UTC'))
            ->withStatus('Error');

        $push2 = (new Push('p2.abcd'))
            ->withApplication($application1)
            ->withDeployment($deployment1)
            ->withBuild($build3)
            ->withCreated(new TimePoint(2015, 8, 16, 12, 0, 0, 'UTC'))
            ->withStatus('Waiting');

        $push3 = (new Push('p3.abcd'))
            ->withApplication($application1)
            ->withDeployment($deployment1)
            ->withBuild($build1)
            ->withCreated(new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC'))
            ->withStatus('Success');

        $push4 = (new Push('p4.abcd'))
            ->withApplication($application1)
            ->withDeployment($deployment1)
            ->withBuild($build2)
            ->withCreated(new TimePoint(2015, 8, 13, 12, 0, 0, 'UTC'))
            ->withStatus('Success');

        // app2
        $push5 = (new Push('p1.efgh'))
            ->withApplication($application2)
            ->withDeployment($deployment2)
            ->withBuild($build1)
            ->withCreated(new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC'))
            ->withStatus('Error');

        $push6 = (new Push('p2.efgh'))
            ->withApplication($application2)
            ->withDeployment($deployment2)
            ->withBuild($build2)
            ->withCreated(new TimePoint(2015, 8, 08, 12, 0, 0, 'UTC'))
            ->withStatus('Waiting');

        $deployment1->withApplication($application1);
        $deployment2->withApplication($application2);

        $pushes = [$push1, $push2, $push3, $push4, $push5, $push6];

        $this->persist($em, array_merge(
            [$environment, $environment2, $application1, $application2],
            [$deployment1, $deployment2],
            [$build1, $build2, $build3],
            $pushes
        ));

        return $pushes;
    }

    public function persist($em, array $resources)
    {
        foreach ($resources as $r) {
            $em->persist($r);
        }

        $em->flush();
    }

    public function dePaginate($paginator)
    {
        $data = [];

        foreach ($paginator as $resource) {
            $data[] = $resource;
        }

        return $data;
    }
}
