<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Utility;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

/**
 * This is a utility class used for generating proxies. Doctrine will attempt to
 * connect to the database during proxy generation. This is bad.
 *
 * We create a new entity manager with a sqlite connection, so doctrine will
 * be shortcircuit-ed and connect to an in-memory db instead.
 */
class DoctrineProxyGenerator
{
    /**
     * @type EntityManager
     */
    private $em;

    /**
     * @type string
     */
    private $output;

    /**
     * @param ContainerInterface $di
     */
    public function __construct(ContainerInterface $di)
    {
        $this->em = $this->buildFakeEntityManager($di, [
            'driver' => 'pdo_sqlite',
            'memory' => true
        ]);

        $this->output = '';
    }

    /**
     * @param ContainerInterface $di
     * @param mixed options
     *
     * @return EntityManagerInterface
     */
    public function buildFakeEntityManager(ContainerInterface $di, $options)
    {
        $em = EntityManager::create(
            $options,
            $di->get('doctrine.config'),
            $di->get('doctrine.em.events')
        );

        $di->get('doctrine.em.configurator')->configure($em);

        return $em;
    }

    /**
     * @return bool
     */
    public function __invoke()
    {
        $metas = $this->em->getMetadataFactory()->getAllMetadata();
        $proxy = $this->em->getProxyFactory();
        $proxyDir = $this->em->getConfiguration()->getProxyDir();

        if (count($metas) === 0) {
            $this->output = "No entities to process.\n";
            return true;
        }

        if (!is_dir($proxyDir)) {
            mkdir($proxyDir, 0777, true);
        }

        $proxyDir = realpath($proxyDir);

        if (!file_exists($proxyDir)) {
            $this->output = sprintf('Proxies destination directory "%s" does not exist.', $proxyDir) . "\n";
            return false;
        }

        if (!is_writable($proxyDir)) {
            $this->output = sprintf('Proxies destination directory "%s" does not have write permissions.', $proxyDir) . "\n";
            return false;
        }

        foreach ($metas as $metadata) {
            $this->output .= sprintf('Processing "%s"', $metadata->name) . "\n";
        }

        // Generate Proxies
        $proxy->generateProxyClasses($metas);

        $this->output .= "\n" . sprintf('Proxy classes generated to "%s"', $proxyDir) . "\n";

        return true;
    }

    /**
     * @return string
     */
    public function output()
    {
        return $this->output;
    }
}
