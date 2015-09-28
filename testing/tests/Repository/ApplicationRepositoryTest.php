<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Repository;

use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Group;
use QL\Hal\Core\Testing\DoctrineTest;

class ApplicationRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Application::CLASS);

        $this->assertSame(ApplicationRepository::CLASS, get_class($repo));
        $this->assertSame(Application::CLASS, $repo->getClassName());
    }

    public function testNoAppsFound()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Application::CLASS);

        $apps = $repo->getGroupedApplications();

        $this->assertSame([], $apps);
    }

    public function test()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Application::CLASS);

        $group1 = (new Group)
            ->withKey('group1')
            ->withName('Gamma Zeta one');
        $group2 = (new Group)
            ->withKey('group2')
            ->withName('Gamma two');

        $app1 = (new Application)
            ->withKey('app1')
            ->withName('Beta 1 from Group 2')
            ->withGroup($group2);
        $app2 = (new Application)
            ->withKey('app2')
            ->withName('Charlie 2 from Group 1')
            ->withGroup($group1);
        $app3 = (new Application)
            ->withKey('app3')
            ->withName('Alpha 3 from Group 2')
            ->withGroup($group2);

        $em->persist($group1);
        $em->persist($group2);
        $em->persist($app1);
        $em->persist($app2);
        $em->persist($app3);
        $em->flush();

        $apps = $repo->getGroupedApplications();

        $this->assertSame([$app2], $apps[1]);
        $this->assertSame([$app3, $app1], $apps[2]);

        // Group 2 is first, which has 2 apps
        $apps = array_shift($apps);
        $this->assertCount(2, $apps);
    }
}
