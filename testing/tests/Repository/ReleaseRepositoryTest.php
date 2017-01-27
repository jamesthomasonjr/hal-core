<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Build;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Organization;
use Hal\Core\Entity\Release;
use Hal\Core\Entity\Target;
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

        $environment = new Environment('1234', 'test');

        $releases = $this->getMockReleases($em, $target1, $target2, $environment);

        // Pull app from first release
        $application1 = $releases[0]->application();

        $actual = $repo->getByApplicationForEnvironment($application1, $environment, 25, 0, '1bcde12345abcde12345abcde12345abcde12345');

        $this->assertCount(1, $actual);

        $actual = $this->dePaginate($actual);
        $this->assertSame($releases[1], $actual[0]);
    }

    public function getMockReleases($em, Target $target1, Target $target2, Environment $environment = null)
    {
        $org = new Organization;
        $application1 = (new Application('12', 'app1', 'App Name'))
            ->withOrganization($org);
        $application2 = (new Application('34', 'app2', 'App Name'))
            ->withOrganization($org);

        $build1 = (new Build('1abcd'))
            ->withStatus('success')
            ->withReference('pull/1234');
        $build2 = (new Build('2abcd'))
            ->withStatus('removed')
            ->withReference('master');
        $build3 = (new Build('3abcd'))
            ->withStatus('success')
            ->withReference('derpbranch')
            ->withCommit('1bcde12345abcde12345abcde12345abcde12345');

        $environment2 = new Environment('5678', 'beta');

        if (!$environment) {
            $environment = new Environment('1234', 'test');
        }

        $build1->withEnvironment($environment);
        $build2->withEnvironment($environment2);
        $build3->withEnvironment($environment);

        // app1
        $release1 = (new Release('1abcd'))
            ->withApplication($application1)
            ->withTarget($target1)
            ->withBuild($build1)
            ->withCreated(new TimePoint(2015, 8, 09, 12, 0, 0, 'UTC'))
            ->withStatus('failure');

        $release2 = (new Release('2abcd'))
            ->withApplication($application1)
            ->withTarget($target1)
            ->withBuild($build3)
            ->withCreated(new TimePoint(2015, 8, 16, 12, 0, 0, 'UTC'))
            ->withStatus('pending');

        $release3 = (new Release('3abcd'))
            ->withApplication($application1)
            ->withTarget($target1)
            ->withBuild($build1)
            ->withCreated(new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC'))
            ->withStatus('success');

        $release4 = (new Release('4abcd'))
            ->withApplication($application1)
            ->withTarget($target1)
            ->withBuild($build2)
            ->withCreated(new TimePoint(2015, 8, 13, 12, 0, 0, 'UTC'))
            ->withStatus('success');

        // app2
        $release5 = (new Release('p1.efgh'))
            ->withApplication($application2)
            ->withTarget($target2)
            ->withBuild($build1)
            ->withCreated(new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC'))
            ->withStatus('failure');

        $release6 = (new Release('p2.efgh'))
            ->withApplication($application2)
            ->withTarget($target2)
            ->withBuild($build2)
            ->withCreated(new TimePoint(2015, 8, 08, 12, 0, 0, 'UTC'))
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
