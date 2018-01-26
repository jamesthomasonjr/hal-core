<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Hal\Core\Entity\User;
use Hal\Core\Entity\System\UserIdentityProvider;
use Hal\Core\Testing\DoctrineTest;
use QL\MCP\Common\Time\TimePoint;

class UserRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(User::class);

        $this->assertSame(UserRepository::class, get_class($repo));
        $this->assertSame(User::class, $repo->getClassName());
    }

    public function testGetPagedUsers()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(User::class);

        $provider = new UserIdentityProvider;
        $user1 = (new User('user1'))->withProvider($provider);
        $user2 = (new User('user2'))->withProvider($provider);
        $user3 = (new User('user3'))->withProvider($provider);
        $user4 = (new User('user4'))->withProvider($provider);

        $this->persist($em, [$provider, $user1, $user2, $user3, $user4]);

        $users = $repo->getPagedResults(2, 1);

        $raw = [];
        foreach ($users as $user) $raw[] = $user;

        // total size
        $this->assertCount(4, $users);

        // page size
        $this->assertCount(2, $raw);

        $this->assertSame($user3, $raw[0]);
        $this->assertSame($user4, $raw[1]);
    }
}
