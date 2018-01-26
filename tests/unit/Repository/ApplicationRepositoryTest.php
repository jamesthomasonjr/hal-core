<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Organization;
use Hal\Core\Testing\DoctrineTest;

class ApplicationRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Application::class);

        $this->assertSame(ApplicationRepository::class, get_class($repo));
        $this->assertSame(Application::class, $repo->getClassName());
    }

    public function testNoAppsFound()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Application::class);

        $apps = $repo->getGroupedApplications();

        $this->assertSame([], $apps);
    }

    public function test()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Application::class);

        $org1 = (new Organization('org1'))->withName('b_org1');
        $org2 = (new Organization('org2'))->withName('a_org2');
        $app1 = $this->buildApplication('app1', 'Beta1 from org2', $org2);
        $app2 = $this->buildApplication('app2', 'Charlie2 from org1', $org1);
        $app3 = $this->buildApplication('app3', 'Alpha1 from org2', $org2);
        $this->persist($em, [$org1, $org2, $app1, $app2, $app3]);

        $apps = $repo->getGroupedApplications();

        $this->assertSame([$app2], $apps['org1']);
        $this->assertSame([$app3, $app1], $apps['org2']);

        // Org 2 is first, which has 2 apps
        $apps = array_shift($apps);
        $this->assertCount(2, $apps);
    }

    public function testGetPagedResults()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Application::class);

        $app1 = new Application('app1');
        $app2 = new Application('app2');
        $app3 = new Application('app3');
        $this->persist($em, [$app1, $app2, $app3]);

        $apps = $repo->getPagedResults(2);

        $raw = [];
        foreach ($apps as $app) $raw[] = $app;

        $this->assertCount(2, $raw);
        $this->assertCount(3, $apps);
    }

    public function buildApplication($id, $name, Organization $org)
    {
        return (new Application($id))
            ->withName($name)
            ->withOrganization($org);
    }
}
