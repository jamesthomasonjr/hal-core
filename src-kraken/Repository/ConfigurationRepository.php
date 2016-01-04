<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Kraken\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use QL\Kraken\Core\Entity\Application;
use QL\Kraken\Core\Entity\Environment;

class ConfigurationRepository extends EntityRepository
{
    const DQL_GET_CONFIGURATIONS = <<<SQL
   SELECT c
     FROM QL\Kraken\Core\Entity\Configuration c
    WHERE c.application = :application
 ORDER BY c.created DESC
SQL;

    const DQL_GET_CONFIGURATIONS_FOR_ENV = <<<SQL
   SELECT c
     FROM QL\Kraken\Core\Entity\Configuration c
    WHERE c.application = :application
      AND c.environment = :environment
 ORDER BY c.created DESC
SQL;

    /**
     * @param Application $application
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getByApplication(Application $application, $limit = 25, $page = 0)
    {
        $dql = self::DQL_GET_CONFIGURATIONS;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page)
            ->setParameter('application', $application);

        return new Paginator($query);
    }

    /**
     * @param Application $application
     * @param Environment $environment
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getByApplicationForEnvironment(Application $application, Environment $environment, $limit = 25, $page = 0)
    {
        $dql = self::DQL_GET_CONFIGURATIONS_FOR_ENV;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page)
            ->setParameter('application', $application)
            ->setParameter('environment', $environment);

        return new Paginator($query);
    }
}
