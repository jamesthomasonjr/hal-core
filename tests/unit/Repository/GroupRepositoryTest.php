<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Group;
use Hal\Core\Testing\DoctrineTest;
use QL\MCP\Common\Time\TimePoint;

class GroupRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Group::class);

        $this->assertSame(GroupRepository::class, get_class($repo));
        $this->assertSame(Group::class, $repo->getClassName());
    }

    public function testNoServersFound()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Group::class);

        $logs = $repo->getPagedResults();

        $this->assertCount(0, $logs);
    }

    public function test()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Group::class);

        $group1 = (new Group)
            ->withType('rsync')
            ->withName('hostname2');

        $group2 = (new Group)
            ->withType('rsync')
            ->withName('hostname3');

        $group3 = (new Group)
            ->withType('rsync')
            ->withName('hostname1');

        $group4 = (new Group)
            ->withType('cd')
            ->withName('us-west-1');

        $group5 = (new Group)
            ->withType('eb')
            ->withName('us-east-1');

        $em->persist($group1);
        $em->persist($group2);
        $em->persist($group3);
        $em->persist($group4);
        $em->persist($group5);
        $em->flush();

        $groups = $repo->getPagedResults(2, 1);

        $raw = [];
        foreach ($groups as $group) $raw[] = $group;

        // total size
        $this->assertCount(5, $groups);

        // page size
        $this->assertCount(2, $raw);

        $this->assertSame($group2, $raw[0]);
        $this->assertSame($group1, $raw[1]);
    }
}
