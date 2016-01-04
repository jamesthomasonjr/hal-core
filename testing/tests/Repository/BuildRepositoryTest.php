<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Repository;

use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Entity\Environment;
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

    public function testGetBuildsWithNoBuildsFound()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $app = (new Application)
            ->withKey('app1')
            ->withName('my app');

        $this->persist($em, [$app]);

        $builds = $repo->getByApplication($app);

        $this->assertCount(0, $builds);
    }

    public function testGetBuildsWithoutFilter()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $app = (new Application)
            ->withKey('app1')
            ->withName('my app');

        $builds = $this->getMockBuilds($app);
        $this->persist($em, array_merge([$app], $builds));

        $results = $repo->getByApplication($app, 2, 1);

        $raw = [];
        foreach ($results as $build) $raw[] = $build;

        // total size
        $this->assertCount(5, $results);

        // page size
        $this->assertCount(2, $raw);

        $this->assertSame($builds[2], $raw[0]);
        $this->assertSame($builds[3], $raw[1]);
    }

    public function testGetBuildsWithFilter()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $app = (new Application)
            ->withKey('app1')
            ->withName('my app');

        $builds = $this->getMockBuilds($app);
        $this->persist($em, array_merge([$app], $builds));

        $results = $repo->getByApplication($app, 25, 0, 'pull/50');

        $raw = [];
        foreach ($results as $build) $raw[] = $build;

        // total size
        $this->assertCount(2, $results);

        // page size
        $this->assertCount(2, $raw);

        $this->assertSame($builds[4], $raw[0]);
        $this->assertSame($builds[1], $raw[1]);
    }

    public function testWithCommitFilter()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $app = (new Application)
            ->withKey('app1')
            ->withName('my app');

        $builds = $this->getMockBuilds($app);
        $this->persist($em, array_merge([$app], $builds));

        $results = $repo->getByApplication($app, 25, 0, '1bcde12345abcde12345abcde12345abcde12345');

        $raw = [];
        foreach ($results as $build) $raw[] = $build;

        // total size
        $this->assertCount(1, $results);

        // page size
        $this->assertCount(1, $raw);

        $this->assertSame($builds[0], $raw[0]);
    }

    public function testGetEnvironmentBuildsWithNoBuildsFound()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $app = (new Application)
            ->withKey('app1')
            ->withName('my app');
        $env = (new Environment)
            ->withId(1234)
            ->withName('test');
        $env2 = (new Environment)
            ->withId(5678)
            ->withName('beta');

        $builds = $this->getMockBuilds($app, $env);
        $this->persist($em, array_merge([$app, $env], $builds));

        $builds = $repo->getByApplicationForEnvironment($app, $env2);

        $this->assertCount(0, $builds);
    }

    public function testGetEnvironmentBuildsWithBuildsFound()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $app = (new Application)
            ->withKey('app1')
            ->withName('my app');
        $env = (new Environment)
            ->withId(1234)
            ->withName('test');

        $builds = $this->getMockBuilds($app, $env);
        $this->persist($em, array_merge([$app, $env], $builds));

        $builds = $repo->getByApplicationForEnvironment($app, $env);

        $this->assertCount(3, $builds);
    }

    public function testGetEnvironmentBuildsWithFilterWithBuildsFound()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Build::CLASS);

        $app = (new Application)
            ->withKey('app1')
            ->withName('my app');
        $env = (new Environment)
            ->withId(1234)
            ->withName('test');

        $builds = $this->getMockBuilds($app, $env);
        $this->persist($em, array_merge([$app, $env], $builds));

        $builds = $repo->getByApplicationForEnvironment($app, $env, 25, 0, '1BCDE12345ABCDE12345ABCDE12345ABCDE12345');

        $this->assertCount(1, $builds);
    }

    public function persist($em, array $resources)
    {
        foreach ($resources as $r) {
            $em->persist($r);
        }

        $em->flush();
    }

    public function getMockBuilds(Application $app, Environment $environment = null)
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

        if ($environment) {
            $build1->withEnvironment($environment);
            $build2->withEnvironment($environment);
            $build3->withEnvironment($environment);
        }

        return [
            $build1,
            $build2,
            $build3,
            $build4,
            $build5
        ];
    }
}
