<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Repository;

use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Push;
use QL\Hal\Core\Testing\DoctrineTest;
use MCP\DataType\Time\TimePoint;

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

        $deployment1 = (new Deployment)
            ->withName('');
        $deployment2 = (new Deployment)
            ->withName('');

        $build1 = (new Build('b1.abcd'))
            ->withStatus('Success');
        $build2 = (new Build('b2.abcd'))
            ->withStatus('Success');

        $push1 = (new Push('p1.abcd'))
            ->withDeployment($deployment1)
            ->withBuild($build1)
            ->withStatus('Success');

        $push2 = (new Push('p2.abcd'))
            ->withDeployment($deployment2)
            ->withBuild($build1)
            ->withStatus('Success');
        $push3 = (new Push('p3.abcd'))
            ->withDeployment($deployment1)
            ->withBuild($build2)
            ->withStatus('Success');

        $em->persist($build1);
        $em->persist($build2);
        $em->persist($deployment1);
        $em->persist($deployment2);
        $em->persist($push1);
        $em->persist($push2);
        $em->persist($push3);

        $em->flush();

        $rollbacks = $repo->getAvailableRollbacksByDeployment($deployment1);

        $raw = [];
        foreach ($rollbacks as $push) $raw[] = $push;

        $this->assertSame([$push1, $push3], $raw);
    }

    public function testGetAllPushesByDeployment()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $deployment1 = (new Deployment)
            ->withName('');
        $deployment2 = (new Deployment)
            ->withName('');

        $build1 = (new Build('b1.abcd'))
            ->withStatus('Success');
        $build2 = (new Build('b2.abcd'))
            ->withStatus('Removed');

        $push1 = (new Push('p1.abcd'))
            ->withDeployment($deployment1)
            ->withBuild($build1)
            ->withStatus('Error');

        $push2 = (new Push('p2.abcd'))
            ->withDeployment($deployment2)
            ->withBuild($build1)
            ->withStatus('Waiting');
        $push3 = (new Push('p3.abcd'))
            ->withDeployment($deployment1)
            ->withBuild($build2)
            ->withStatus('Success');

        $em->persist($build1);
        $em->persist($build2);
        $em->persist($deployment1);
        $em->persist($deployment2);
        $em->persist($push1);
        $em->persist($push2);
        $em->persist($push3);

        $em->flush();

        $pushes = $repo->getByDeployment($deployment2);

        $raw = [];
        foreach ($pushes as $push) $raw[] = $push;

        $this->assertSame([$push2], $raw);
    }

    public function testGetAllPushesByApplication()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $application1 = (new Application)
            ->withId(12)
            ->withKey('app1')
            ->withName('App Name');
        $application2 = (new Application)
            ->withId(34)
            ->withKey('app2')
            ->withName('App Name');

        $build1 = (new Build('b1.abcd'))
            ->withStatus('Success');
        $build2 = (new Build('b2.abcd'))
            ->withStatus('Removed');

        $push1 = (new Push('p1.abcd'))
            ->withApplication($application1)
            ->withBuild($build1)
            ->withStatus('Error');

        $push2 = (new Push('p2.abcd'))
            ->withApplication($application1)
            ->withBuild($build1)
            ->withStatus('Waiting');

        $push3 = (new Push('p3.abcd'))
            ->withApplication($application2)
            ->withBuild($build2)
            ->withStatus('Success');

        $em->persist($build1);
        $em->persist($build2);
        $em->persist($application1);
        $em->persist($application2);
        $em->persist($push1);
        $em->persist($push2);
        $em->persist($push3);

        $em->flush();

        $pushes = $repo->getByApplication($application1);

        $raw = [];
        foreach ($pushes as $push) $raw[] = $push;

        $this->assertSame([$push1, $push2], $raw);
    }

    public function testGetAllPushesByApplicationFilteredByRef()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $application1 = (new Application)
            ->withId(12)
            ->withKey('app1')
            ->withName('App Name');
        $application2 = (new Application)
            ->withId(34)
            ->withKey('app2')
            ->withName('App Name');

        $build1 = (new Build('b1.abcd'))
            ->withStatus('Success');
        $build2 = (new Build('b2.abcd'))
            ->withStatus('Removed')
            ->withBranch('master');

        $push1 = (new Push('p1.abcd'))
            ->withApplication($application1)
            ->withBuild($build1)
            ->withStatus('Error');

        $push2 = (new Push('p2.abcd'))
            ->withApplication($application1)
            ->withBuild($build2)
            ->withStatus('Waiting');

        $push3 = (new Push('p3.abcd'))
            ->withApplication($application2)
            ->withBuild($build2)
            ->withStatus('Success');

        $em->persist($build1);
        $em->persist($build2);
        $em->persist($application1);
        $em->persist($application2);
        $em->persist($push1);
        $em->persist($push2);
        $em->persist($push3);

        $em->flush();

        $pushes = $repo->getByApplication($application1, 25, 0, 'master');

        $raw = [];
        foreach ($pushes as $push) $raw[] = $push;

        $this->assertSame([$push2], $raw);
    }

    public function testGetMostRecentPushByDeployment()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $deployment1 = (new Deployment)
            ->withId(12);
        $deployment2 = (new Deployment)
            ->withId(34);

        $push1 = (new Push('p1.abcd'))
            ->withDeployment($deployment1)
            ->withCreated(new TimePoint(2015, 8, 15, 10, 0, 0, 'UTC'))
            ->withStatus('Error');

        $push2 = (new Push('p2.abcd'))
            ->withDeployment($deployment1)
            ->withCreated(new TimePoint(2015, 8, 15, 11, 0, 0, 'UTC'))
            ->withStatus('Waiting');

        $push3 = (new Push('p3.abcd'))
            ->withDeployment($deployment2)
            ->withCreated(new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC'))
            ->withStatus('Success');

        $em->persist($deployment1);
        $em->persist($deployment2);
        $em->persist($push1);
        $em->persist($push2);
        $em->persist($push3);

        $em->flush();

        $push = $repo->getMostRecentByDeployment($deployment1);

        $this->assertSame($push2, $push);
    }
    public function testGetMostRecentSuccessfulPushByDeployment()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Push::CLASS);

        $deployment1 = (new Deployment)
            ->withId(12);

        $push1 = (new Push('p1.abcd'))
            ->withDeployment($deployment1)
            ->withCreated(new TimePoint(2015, 8, 15, 10, 0, 0, 'UTC'))
            ->withStatus('Error');

        $push2 = (new Push('p2.abcd'))
            ->withDeployment($deployment1)
            ->withCreated(new TimePoint(2015, 8, 15, 11, 0, 0, 'UTC'))
            ->withStatus('Waiting');

        $push3 = (new Push('p3.abcd'))
            ->withDeployment($deployment1)
            ->withCreated(new TimePoint(2015, 8, 15, 9, 0, 0, 'UTC'))
            ->withStatus('Success');

        $em->persist($deployment1);
        $em->persist($push1);
        $em->persist($push2);
        $em->persist($push3);

        $em->flush();

        $push = $repo->getMostRecentSuccessByDeployment($deployment1);

        $this->assertSame($push3, $push);
    }
}
