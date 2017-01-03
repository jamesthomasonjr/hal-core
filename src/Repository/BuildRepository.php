<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use QL\Hal\Core\DoctrinePagination\Mysql57Paginator as Paginator;
use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Environment;

class BuildRepository extends EntityRepository
{
    const REGEX_COMMIT = '#^[0-9a-f]{40}$#i';

    const DQL_BY_APPLICATION = <<<SQL
   SELECT b
     FROM QL\Hal\Core\Entity\Build b
    WHERE b.application = :application
 ORDER BY b.created DESC
SQL;
    const DQL_BY_APPLICATION_WITH_REF_FILTER = <<<SQL
   SELECT b
     FROM QL\Hal\Core\Entity\Build b
    WHERE b.application = :application
      AND b.branch = :ref
 ORDER BY b.created DESC
SQL;
    const DQL_BY_APPLICATION_WITH_SHA_FILTER = <<<SQL
   SELECT b
     FROM QL\Hal\Core\Entity\Build b
    WHERE b.application = :application
      AND b.commit = :ref
 ORDER BY b.created DESC
SQL;

    const DQL_BY_APPLICATION_AND_ENV = <<<SQL
   SELECT b
     FROM QL\Hal\Core\Entity\Build b
    WHERE b.application = :application
      AND b.environment = :environment
 ORDER BY b.created DESC
SQL;
    const DQL_BY_APPLICATION_AND_ENV_WITH_REF_FILTER = <<<SQL
   SELECT b
     FROM QL\Hal\Core\Entity\Build b
    WHERE b.application = :application
      AND b.environment = :environment
      AND b.branch = :ref
 ORDER BY b.created DESC
SQL;
    const DQL_BY_APPLICATION_AND_ENV_WITH_SHA_FILTER = <<<SQL
   SELECT b
     FROM QL\Hal\Core\Entity\Build b
    WHERE b.application = :application
      AND b.environment = :environment
      AND b.commit = :ref
 ORDER BY b.created DESC
SQL;

    /**
     * Get all builds for an application.
     *
     * @param Application $application
     * @param int $limit
     * @param int $page
     * @param string|null $filter
     *
     * @return Paginator
     */
    public function getByApplication(Application $application, $limit = 25, $page = 0, $filter = null)
    {
        $dql = self::DQL_BY_APPLICATION;
        if ($filter) {
            $dql = self::DQL_BY_APPLICATION_WITH_REF_FILTER;

            // is a commit sha
            if (preg_match(self::REGEX_COMMIT, $filter) === 1) {
                $dql = self::DQL_BY_APPLICATION_WITH_SHA_FILTER;
                $filter = strtolower($filter);
            }
        }

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page)
            ->setParameter('application', $application);

        if ($filter) {
            $query->setParameter('ref', $filter);
        }

        return new Paginator($query);
    }

    /**
     * Get all builds for an application and environment.
     *
     * @param Application $application
     * @param Environment $environment
     * @param int $limit
     * @param int $page
     * @param string|null $filter
     *
     * @return Paginator
     */
    public function getByApplicationForEnvironment(Application $application, Environment $environment, $limit = 25, $page = 0, $filter = null)
    {
        $dql = self::DQL_BY_APPLICATION_AND_ENV;
        if ($filter) {
            $dql = self::DQL_BY_APPLICATION_AND_ENV_WITH_REF_FILTER;

            // is a commit sha
            if (preg_match(self::REGEX_COMMIT, $filter) === 1) {
                $dql = self::DQL_BY_APPLICATION_AND_ENV_WITH_SHA_FILTER;
                $filter = strtolower($filter);
            }
        }

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page)
            ->setParameter('environment', $environment)
            ->setParameter('application', $application);

        if ($filter) {
            $query->setParameter('ref', $filter);
        }

        return new Paginator($query);
    }
}
