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

        $org1 = new Organization('1abcd', 'org1', 'Gamma zeta');
        $org2 = new Organization('2efgh', 'org2', 'Gamma two');

        $app1 = $this->buildApplication('app1', 'Beta 1 from org 2', $org2);
        $app2 = $this->buildApplication('app2', 'Charlie 2 from org 1', $org1);
        $app3 = $this->buildApplication('app3', 'Alpha 1 from org 2', $org2);

        $em->persist($org1);
        $em->persist($org2);
        $em->persist($app1);
        $em->persist($app2);
        $em->persist($app3);
        $em->flush();

        $apps = $repo->getGroupedApplications();

        $this->assertSame([$app2], $apps['1abcd']);
        $this->assertSame([$app3, $app1], $apps['2efgh']);

        // Org 2 is first, which has 2 apps
        $apps = array_shift($apps);
        $this->assertCount(2, $apps);
    }

    public function buildApplication($identifier, $name, Organization $org)
    {
        return (new Application(null, $identifier, $name))
            ->withOrganization($org);
    }
}
