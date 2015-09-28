<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Repository;

use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Testing\DoctrineTest;
use MCP\DataType\Time\TimePoint;

class BuildRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $this->assertSame(BuildRepository::CLASS, get_class($repo));
        $this->assertSame(Build::CLASS, $repo->getClassName());
    }

    public function testBuildsFound()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $app = (new Application)
            ->withKey('app1')
            ->withName('my app');

        $em->persist($app);
        $em->flush();

        $builds = $repo->getByApplication($app);

        $this->assertCount(0, $builds);
    }

    public function test()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $app = (new Application)
            ->withKey('app1')
            ->withName('my app');

        $builds = $this->getMockBuilds($app);
        foreach ($builds as $b) $em->persist($b);
        unset($builds[0]);
        $em->flush();

        $results = $repo->getByApplication($app, 2, 1);

        $raw = [];
        foreach ($results as $build) $raw[] = $build;

        // total size
        $this->assertCount(5, $results);

        // page size
        $this->assertCount(2, $raw);

        $this->assertSame($builds[3], $raw[0]);
        $this->assertSame($builds[4], $raw[1]);
    }

    public function testWithFilter()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $app = (new Application)
            ->withKey('app1')
            ->withName('my app');

        $builds = $this->getMockBuilds($app);
        foreach ($builds as $b) $em->persist($b);
        unset($builds[0]);
        $em->flush();

        $results = $repo->getByApplication($app, 25, 0, 'pull/50');

        $raw = [];
        foreach ($results as $build) $raw[] = $build;

        // total size
        $this->assertCount(2, $results);

        // page size
        $this->assertCount(2, $raw);

        $this->assertSame($builds[5], $raw[0]);
        $this->assertSame($builds[2], $raw[1]);
    }

    public function testWithCommitFilter()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $app = (new Application)
            ->withKey('app1')
            ->withName('my app');

        $builds = $this->getMockBuilds($app);
        foreach ($builds as $b) $em->persist($b);
        unset($builds[0]);
        $em->flush();

        $results = $repo->getByApplication($app, 25, 0, '1bcde12345abcde12345abcde12345abcde12345');

        $raw = [];
        foreach ($results as $build) $raw[] = $build;

        // total size
        $this->assertCount(1, $results);

        // page size
        $this->assertCount(1, $raw);

        $this->assertSame($builds[1], $raw[0]);
    }

    public function getMockBuilds(Application $app)
    {
        $build1 = (new Build('ab'))
            ->withCreated(new TimePoint(2015, 08, 15, 12, 0, 0, 'UTC'))
            ->withApplication($app)
            ->withStatus('Waiting')
            ->withCommit('1bcde12345abcde12345abcde12345abcde12345')
            ->withBranch('master');

        $build2 = (new Build('cd'))
            ->withCreated(new TimePoint(2015, 08, 15, 15, 0, 0, 'UTC'))
            ->withApplication($app)
            ->withStatus('Waiting')
            ->withCommit('2bcde12345abcde12345abcde12345abcde12345')
            ->withBranch('pull/50');

        $build3 = (new Build('ef'))
            ->withCreated(new TimePoint(2015, 08, 15, 14, 0, 0, 'UTC'))
            ->withApplication($app)
            ->withStatus('Building')
            ->withCommit('3bcde12345abcde12345abcde12345abcde12345')
            ->withBranch('mybranch');

        $build4 = (new Build('gh'))
            ->withCreated(new TimePoint(2015, 08, 15, 13, 0, 0, 'UTC'))
            ->withApplication($app)
            ->withStatus('Building')
            ->withCommit('4bcde12345abcde12345abcde12345abcde12345')
            ->withBranch('master');

        $build5 = (new Build('ij'))
            ->withCreated(new TimePoint(2015, 08, 15, 20, 0, 0, 'UTC'))
            ->withApplication($app)
            ->withStatus('Success')
            ->withCommit('5bcde12345abcde12345abcde12345abcde12345')
            ->withBranch('pull/50');

        return [
            $app,
            $build1,
            $build2,
            $build3,
            $build4,
            $build5
        ];
    }
}
