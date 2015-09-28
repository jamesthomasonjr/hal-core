<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Repository;

use QL\Hal\Core\Entity\Environment;
use QL\Hal\Core\Entity\Server;
use QL\Hal\Core\Testing\DoctrineTest;
use MCP\DataType\Time\TimePoint;

class ServerRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Server::CLASS);

        $this->assertSame(ServerRepository::CLASS, get_class($repo));
        $this->assertSame(Server::CLASS, $repo->getClassName());
    }

    public function testNoServersFound()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Server::CLASS);

        $logs = $repo->getPaginatedServers();

        $this->assertCount(0, $logs);
    }

    public function test()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Server::CLASS);

        $server1 = (new Server)
            ->withType('rsync')
            ->withName('hostname2');

        $server2 = (new Server)
            ->withType('rsync')
            ->withName('hostname3');

        $server3 = (new Server)
            ->withType('rsync')
            ->withName('hostname1');

        $server4 = (new Server)
            ->withType('cd')
            ->withName('us-west-1');

        $server5 = (new Server)
            ->withType('eb')
            ->withName('us-east-1');

        $em->persist($server1);
        $em->persist($server2);
        $em->persist($server3);
        $em->persist($server4);
        $em->persist($server5);
        $em->flush();

        $servers = $repo->getPaginatedServers(2, 1);

        $raw = [];
        foreach ($servers as $server) $raw[] = $server;

        // total size
        $this->assertCount(5, $servers);

        // page size
        $this->assertCount(2, $raw);

        $this->assertSame($server2, $raw[0]);
        $this->assertSame($server1, $raw[1]);
    }
}
