<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Repository;

use QL\Hal\Core\Testing\DoctrineTest;
use QL\Kraken\Core\Entity\AuditLog;
use QL\MCP\Common\Time\TimePoint;

class AuditLogRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(AuditLog::CLASS);

        $this->assertSame(AuditLogRepository::CLASS, get_class($repo));
        $this->assertSame(AuditLog::CLASS, $repo->getClassName());
    }

    public function testNoLogsFound()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(AuditLog::CLASS);

        $logs = $repo->getPagedResults();

        $this->assertCount(0, $logs);
    }

    public function test()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(AuditLog::CLASS);

        $log1 = (new AuditLog('ab'))
            ->withAction('action:1')
            ->withCreated(new TimePoint(2015, 08, 15, 12, 0, 0, 'UTC'));
        $log2 = (new AuditLog('cd'))
            ->withAction('action:1')
            ->withCreated(new TimePoint(2015, 08, 15, 15, 0, 0, 'UTC'));
        $log3 = (new AuditLog('ef'))
            ->withAction('action:1')
            ->withCreated(new TimePoint(2015, 08, 15, 14, 0, 0, 'UTC'));
        $log4 = (new AuditLog('gh'))
            ->withAction('action:1')
            ->withCreated(new TimePoint(2015, 08, 15, 13, 0, 0, 'UTC'));
        $log5 = (new AuditLog('ij'))
            ->withAction('action:1')
            ->withCreated(new TimePoint(2015, 08, 15, 20, 0, 0, 'UTC'));

        $em->persist($log1);
        $em->persist($log2);
        $em->persist($log3);
        $em->persist($log4);
        $em->persist($log5);
        $em->flush();

        $logs = $repo->getPagedResults(2, 1);

        $raw = [];
        foreach ($logs as $log) {
            $raw[] = $log;
        }

        // total size
        $this->assertCount(5, $logs);

        // page size
        $this->assertCount(2, $raw);

        $this->assertSame($log3, $raw[0]);
        $this->assertSame($log4, $raw[1]);
    }
}
