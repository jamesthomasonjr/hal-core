<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Hal\Core\Entity\Target;
use Hal\Core\Entity\TargetPool;
use Hal\Core\Entity\TargetView;
use Hal\Core\Testing\DoctrineTest;

class TargetPoolTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(TargetPool::class);

        $this->assertSame(TargetPoolRepository::class, get_class($repo));
        $this->assertSame(TargetPool::class, $repo->getClassName());
    }

    public function testGetPoolForAViewAndTargetPair()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(TargetPool::class);

        $target = new Target('1234');
        $view = new TargetView('abcde');

        $em->persist($target);
        $em->persist($view);
        $em->flush();

        $pools = $repo->getPoolForViewAndDeployment($view, $target);

        $this->assertSame([], $pools);
    }
}
