<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository\JobType;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Organization;
use Hal\Core\Entity\Target;
use Hal\Core\Entity\JobType\Build;
use Hal\Core\Entity\JobType\Release;
use Hal\Core\Testing\DoctrineTest;
use QL\MCP\Common\Time\TimePoint;

class ReleaseRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Release::class);

        $this->assertSame(ReleaseRepository::class, get_class($repo));
        $this->assertSame(Release::class, $repo->getClassName());
    }

    public function testGetAvailableRollbacksByTarget()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Release::class);

        $target1 = new Target;
        $target2 = new Target;

        $releases = $this->getMockReleases($em, $target1, $target2);

        $rollbacks = $repo->getAvailableRollbacksByTarget($target1);

        $this->assertCount(1, $rollbacks);

        $actual = $this->dePaginate($rollbacks);
        $this->assertSame($releases[2], $actual[0]);
    }

    public function testGetAllReleasesByTarget()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Release::class);

        $target1 = new Target;
        $target2 = new Target;

        $releases = $this->getMockReleases($em, $target1, $target2);

        $actual = $repo->getByTarget($target2);

        $this->assertCount(2, $actual);

        $actual = $this->dePaginate($actual);
        $this->assertSame($releases[4], $actual[0]);
        $this->assertSame($releases[5], $actual[1]);
    }

    public function testGetAllReleasesByApplication()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Release::class);

        $target1 = new Target;
        $target2 = new Target;

        $releases = $this->getMockReleases($em, $target1, $target2);

        // Pull app from first push
        $application1 = $releases[0]->application();

        $actual = $repo->getByApplication($application1);

        $this->assertCount(4, $actual);
    }

    public function testGetAllReleasesByApplicationFilteredByRef()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Release::class);

        $target1 = new Target;
        $target2 = new Target;

        $releases = $this->getMockReleases($em, $target1, $target2);

        // Pull app from first push
        $application1 = $releases[0]->application();

        $actual = $repo->getByApplication($application1, 25, 0, 'master');

        $this->assertCount(1, $actual);

        $actual = $this->dePaginate($actual);
        $this->assertSame($releases[3], $actual[0]);
    }

    public function testGetAllPushesByApplicationAndEnvironmentFilteredByRef()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Release::class);

        $target1 = new Target;
        $target2 = new Target;

        $environment = new Environment('test');

        $releases = $this->getMockReleases($em, $target1, $target2, $environment);

        // Pull app from first release
        $application1 = $releases[0]->application();

        $actual = $repo->getByApplicationForEnvironment($application1, $environment, 25, 0, '1bcde12345abcde12345abcde12345abcde12345');

        $this->assertCount(1, $actual);

        $actual = $this->dePaginate($actual);
        $this->assertSame($releases[1], $actual[0]);
    }

    public function testGetPagedResults()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Release::class);

        $target1 = new Target;
        $target2 = new Target;

        $env = new Environment('test');

        $releases = $this->getMockReleases($em, $target1, $target2, $env);

        $releases = $repo->getPagedResults(5);

        $raw = [];
        foreach ($releases as $release) $raw[] = $release;

        $this->assertCount(5, $raw);
        $this->assertCount(6, $releases);
    }

    public function getMockReleases($em, Target $target1, Target $target2, Environment $environment = null)
    {
        $org = new Organization;
        $application1 = (new Application('app1'))
            ->withOrganization($org);
        $application2 = (new Application('app2'))
            ->withOrganization($org);

        $build1 = (new Build('1b'))
            ->withStatus('success')
            ->withReference('pull/1234');
        $build2 = (new Build('2b'))
            ->withStatus('removed')
            ->withReference('master');
        $build3 = (new Build('3b'))
            ->withStatus('success')
            ->withReference('derpbranch')
            ->withCommit('1bcde12345abcde12345abcde12345abcde12345');

        $environment2 = new Environment('beta');

        if (!$environment) {
            $environment = new Environment('test');
        }

        $build1->withEnvironment($environment);
        $build2->withEnvironment($environment2);
        $build3->withEnvironment($environment);

        // app1
        $release1 = (new Release('1abcd', new TimePoint(2015, 8, 9, 12, 0, 0, 'UTC')))
            ->withApplication($application1)
            ->withTarget($target1)
            ->withBuild($build1)
            ->withStatus('failure');

        $release2 = (new Release('2abcd', new TimePoint(2015, 8, 16, 12, 0, 0, 'UTC')))
            ->withApplication($application1)
            ->withTarget($target1)
            ->withBuild($build3)
            ->withStatus('pending');

        $release3 = (new Release('3abcd', new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC')))
            ->withApplication($application1)
            ->withTarget($target1)
            ->withBuild($build1)
            ->withStatus('success');

        $release4 = (new Release('4abcd', new TimePoint(2015, 8, 13, 12, 0, 0, 'UTC')))
            ->withApplication($application1)
            ->withTarget($target1)
            ->withBuild($build2)
            ->withStatus('success');

        // app2
        $release5 = (new Release('5efgh', new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC')))
            ->withApplication($application2)
            ->withTarget($target2)
            ->withBuild($build1)
            ->withStatus('failure');

        $release6 = (new Release('6efgh', new TimePoint(2015, 8, 8, 12, 0, 0, 'UTC')))
            ->withApplication($application2)
            ->withTarget($target2)
            ->withBuild($build2)
            ->withStatus('pending');

        $target1->withApplication($application1);
        $target2->withApplication($application2);

        $releases = [$release1, $release2, $release3, $release4, $release5, $release6];

        $this->persist($em, array_merge(
            [$org, $environment, $environment2, $application1, $application2],
            [$target1, $target2],
            [$build1, $build2, $build3],
            $releases
        ));

        return $releases;
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
