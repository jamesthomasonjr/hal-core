<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository\System;

use Hal\Core\Entity\System\VersionControlProvider;
use Hal\Core\Testing\DoctrineTest;

class VersionControlProviderRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(VersionControlProvider::class);

        $this->assertSame(VersionControlProviderRepository::class, get_class($repo));
        $this->assertSame(VersionControlProvider::class, $repo->getClassName());
    }

    public function testGetPagedResults()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(VersionControlProvider::class);

        $vcs1 = (new VersionControlProvider())->withName('vcs1');
        $vcs2 = (new VersionControlProvider())->withName('vcs2');
        $vcs3 = (new VersionControlProvider())->withName('vcs3');
        $vcs4 = (new VersionControlProvider())->withName('vcs4');

        $this->persist($em, [$vcs1, $vcs2, $vcs3, $vcs4]);

        $vcss = $repo->getPagedResults(2, 1);

        $raw = [];
        foreach ($vcss as $vcs) $raw[] = $vcs;

        // total size
        $this->assertCount(4, $vcss);

        // page size
        $this->assertCount(2, $raw);

        $this->assertSame($vcs3, $raw[0]);
        $this->assertSame($vcs4, $raw[1]);
    }
}
