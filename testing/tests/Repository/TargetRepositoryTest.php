<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Group;
use Hal\Core\Entity\Organization;
use Hal\Core\Entity\Target;
use Hal\Core\Testing\DoctrineTest;

class TargetTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Target::class);

        $this->assertSame(TargetRepository::class, get_class($repo));
        $this->assertSame(Target::class, $repo->getClassName());
    }

    public function testGetTargetsByApplicationAndEnvironment()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Target::class);

        $org = new Organization;
        $application = (new Application('12', 'app1', 'App Name'))
            ->withOrganization($org);
        $env = new Environment('34');

        $group = (new Group('56', 'rsync'))
            ->withEnvironment($env);

        $target1 = (new Target('1234'))
            ->withApplication($application)
            ->withGroup($group);
        $target2 = (new Target('5678'))
            ->withApplication($application)
            ->withGroup($group);

        $em->persist($org);
        $em->persist($application);
        $em->persist($env);
        $em->persist($group);

        $em->persist($target1);
        $em->persist($target2);
        $em->flush();

        $deployments = $repo->getByApplicationAndEnvironment($application, $env);

        $this->assertSame([$target1, $target2], $deployments);
    }
}
