<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Hal\Core\Entity\Environment;
use Hal\Core\Entity\TargetTemplate;
use Hal\Core\Testing\DoctrineTest;
use QL\MCP\Common\Time\TimePoint;

class TargetTemplateRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(TargetTemplate::class);

        $this->assertSame(TargetTemplateRepository::class, get_class($repo));
        $this->assertSame(TargetTemplate::class, $repo->getClassName());
    }

    public function testNoServersFound()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(TargetTemplate::class);

        $logs = $repo->getPagedResults();

        $this->assertCount(0, $logs);
    }

    public function test()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(TargetTemplate::class);

        $tpl1 = (new TargetTemplate)
            ->withType('rsync')
            ->withName('hostname2');

        $tpl2 = (new TargetTemplate)
            ->withType('rsync')
            ->withName('hostname3');

        $tpl3 = (new TargetTemplate)
            ->withType('rsync')
            ->withName('hostname1');

        $tpl4 = (new TargetTemplate)
            ->withType('cd')
            ->withName('us-west-1');

        $tpl5 = (new TargetTemplate)
            ->withType('eb')
            ->withName('us-east-1');

        $this->persist($em, [$tpl1, $tpl2, $tpl3, $tpl4, $tpl5]);

        $templates = $repo->getPagedResults(2, 1);

        $raw = [];
        foreach ($templates as $template) $raw[] = $template;

        // total size
        $this->assertCount(5, $templates);

        // page size
        $this->assertCount(2, $raw);

        $this->assertSame($tpl2, $raw[0]);
        $this->assertSame($tpl1, $raw[1]);
    }
}
