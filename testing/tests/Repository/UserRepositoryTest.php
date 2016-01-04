<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Repository;

use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Entity\Push;
use QL\Hal\Core\Entity\User;
use QL\Hal\Core\Testing\DoctrineTest;
use MCP\DataType\Time\TimePoint;

class UserRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(User::CLASS);

        $this->assertSame(UserRepository::CLASS, get_class($repo));
        $this->assertSame(User::CLASS, $repo->getClassName());
    }

    public function testGetRecentlyBuiltApplications()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(User::CLASS);

        $app1 = (new Application)
            ->withKey('abcd')
            ->withName('abcd')
            ->withId(12);
        $app2 = (new Application)
            ->withKey('efgh')
            ->withName('efgh')
            ->withId(34);
        $app3 = (new Application)
            ->withKey('ijkl')
            ->withName('ijkl')
            ->withId(56);

        $user = (new User)
            ->withId(12345);

        $build1 = (new Build('b1.1234'))
            ->withStatus('Success')
            ->withUser($user)
            ->withApplication($app1)
            ->withCreated(new TimePoint(2015, 9, 15, 12, 0, 0, 'UTC'));

        $build2 = (new Build('b2.1234'))
            ->withStatus('Success')
            ->withUser($user)
            ->withApplication($app2)
            ->withCreated(new TimePoint(2015, 9, 15, 12, 0, 0, 'UTC'));

        $build3 = (new Build('b3.1234'))
            ->withStatus('Success')
            ->withUser($user)
            ->withApplication($app3)
            ->withCreated(new TimePoint(2015, 8, 15, 12, 0, 0, 'UTC'));

        $em->persist($app1);
        $em->persist($app2);
        $em->persist($app3);
        $em->persist($user);
        $em->persist($build1);
        $em->persist($build2);
        $em->persist($build3);
        $em->flush();

        $apps = $repo->getUsersRecentApplications($user, new TimePoint(2015, 8, 25, 12, 0, 0, 'UTC'));

        $this->assertSame([$app1, $app2], $apps);
    }

    public function testGetPagedUsers()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(User::CLASS);

        $user1 = (new User)
            ->withHandle('a1')
            ->withId(12);

        $user2 = (new User)
            ->withHandle('b1')
            ->withId(34);

        $user3 = (new User)
            ->withHandle('c1')
            ->withId(56);

        $user4 = (new User)
            ->withHandle('d1')
            ->withId(78);

        $em->persist($user1);
        $em->persist($user2);
        $em->persist($user3);
        $em->persist($user4);
        $em->flush();

        $users = $repo->getPaginatedUsers(2, 1);

        $raw = [];
        foreach ($users as $user) $raw[] = $user;

        // total size
        $this->assertCount(4, $users);

        // page size
        $this->assertCount(2, $raw);

        $this->assertSame($user3, $raw[0]);
        $this->assertSame($user4, $raw[1]);
    }

    public function testGetBuildCount()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(User::CLASS);

        $user1 = (new User)
            ->withHandle('a1')
            ->withId(12);

        $user2 = (new User)
            ->withHandle('b1')
            ->withId(34);

        $user3 = (new User)
            ->withHandle('c1')
            ->withId(56);

        $build1 = (new Build('b1.1234'))
            ->withStatus('Success')
            ->withUser($user1);
        $build2 = (new Build('b2.1234'))
            ->withStatus('Success')
            ->withUser($user1);
        $build3 = (new Build('b3.1234'))
            ->withStatus('Success')
            ->withUser($user2);
        $build4 = (new Build('b4.1234'))
            ->withStatus('Success')
            ->withUser($user3);

        $em->persist($user1);
        $em->persist($user2);
        $em->persist($user3);

        $em->persist($build1);
        $em->persist($build2);
        $em->persist($build3);
        $em->persist($build4);
        $em->flush();

        $builds = $repo->getBuildCount($user1);

        $this->assertSame(2, $builds);
    }

    public function testGetPushCount()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(User::CLASS);

        $user1 = (new User)
            ->withHandle('a1')
            ->withId(12);

        $user2 = (new User)
            ->withHandle('b1')
            ->withId(34);

        $user3 = (new User)
            ->withHandle('c1')
            ->withId(56);

        $push1 = (new Push('p1.1234'))
            ->withStatus('Success')
            ->withUser($user1);
        $push2 = (new Push('p2.1234'))
            ->withStatus('Success')
            ->withUser($user1);
        $push3 = (new Push('p3.1234'))
            ->withStatus('Success')
            ->withUser($user2);
        $push4 = (new Push('p4.1234'))
            ->withStatus('Success')
            ->withUser($user3);

        $em->persist($user1);
        $em->persist($user2);
        $em->persist($user3);

        $em->persist($push1);
        $em->persist($push2);
        $em->persist($push3);
        $em->persist($push4);
        $em->flush();

        $pushes = $repo->getPushCount($user3);

        $this->assertSame(1, $pushes);
    }
}
