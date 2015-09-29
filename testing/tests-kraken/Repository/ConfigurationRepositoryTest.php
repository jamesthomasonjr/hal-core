<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Kraken\Core\Repository;

use QL\Kraken\Core\Entity\Application;
use QL\Kraken\Core\Entity\Environment;
use QL\Kraken\Core\Entity\Configuration;
use QL\Hal\Core\Testing\DoctrineTest;
use MCP\DataType\Time\TimePoint;

class ConfigurationRepositoryTest extends DoctrineTest
{
    public function testRepositoryIsCorrect()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Configuration::CLASS);

        $this->assertSame(ConfigurationRepository::CLASS, get_class($repo));
        $this->assertSame(Configuration::CLASS, $repo->getClassName());
    }

    public function testGetConfigurationForApplication()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Configuration::CLASS);

        $app = new Application('abcd');

        $config1 = (new Configuration('ab'))
            ->withApplication($app);

        $config2 = (new Configuration('cd'))
            ->withApplication($app);

        $config3 = (new Configuration('ef'))
            ->withApplication($app);

        $em->persist($app);
        $em->persist($config1);
        $em->persist($config2);
        $em->persist($config3);
        $em->flush();

        $configurations = $repo->getByApplication($app);

        $this->assertCount(3, $configurations);
    }

    public function testGetConfigurationForApplicationAndEnvironment()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Configuration::CLASS);

        $app = new Application('abcd');
        $env = new Environment('efgh');

        $config1 = (new Configuration('ab'))
            ->withApplication($app);

        $config2 = (new Configuration('cd'))
            ->withEnvironment($env)
            ->withApplication($app);

        $config3 = (new Configuration('ef'))
            ->withApplication($app);

        $em->persist($app);
        $em->persist($env);
        $em->persist($config1);
        $em->persist($config2);
        $em->persist($config3);
        $em->flush();

        $configurations = $repo->getByApplicationForEnvironment($app, $env);

        $raw = [];
        foreach ($configurations as $config) $raw[] = $config;

        // total size
        $this->assertCount(1, $configurations);

        // page size
        $this->assertCount(1, $configurations);

        $this->assertSame($config2, $raw[0]);
    }
}
