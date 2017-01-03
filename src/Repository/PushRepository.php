<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use QL\Hal\Core\DoctrinePagination\Mysql57Paginator as Paginator;
use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Environment;
use QL\Hal\Core\Entity\Push;
use QL\Hal\Core\Entity\Server;
use QL\Hal\Core\Entity\User;

class PushRepository extends EntityRepository
{
    const REGEX_COMMIT = '#^[0-9a-f]{40}$#i';

    const DQL_ROLLBACKS = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
     JOIN p.deployment d
     JOIN p.build b
    WHERE
        p.deployment = :deployment AND
        p.status = :pushStatus AND
        b.status = :buildStatus
 ORDER BY p.created DESC
SQL;

    const DQL_BY_DEPLOYMENT = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
     JOIN p.deployment d
    WHERE
        p.deployment = :deployment
 ORDER BY p.created DESC
SQL;

    const DQL_BY_APPLICATION = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
    WHERE p.application = :application
 ORDER BY p.created DESC
SQL;
    const DQL_BY_APPLICATION_WITH_REF_FILTER = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
     JOIN p.build b
    WHERE p.application = :application
      AND b.branch = :ref
 ORDER BY p.created DESC
SQL;
    const DQL_BY_APPLICATION_WITH_SHA_FILTER = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
     JOIN p.build b
    WHERE p.application = :application
      AND b.commit = :ref
 ORDER BY p.created DESC
SQL;

    const DQL_BY_APPLICATION_AND_ENV = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
     JOIN p.build b
    WHERE p.application = :application
      AND b.environment = :environment
 ORDER BY p.created DESC
SQL;
    const DQL_BY_APPLICATION_AND_ENV_WITH_REF_FILTER = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
     JOIN p.build b
    WHERE p.application = :application
      AND b.environment = :environment
      AND b.branch = :ref
 ORDER BY p.created DESC
SQL;
    const DQL_BY_APPLICATION_AND_ENV_WITH_SHA_FILTER = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
     JOIN p.build b
    WHERE p.application = :application
      AND b.environment = :environment
      AND b.commit = :ref
 ORDER BY p.created DESC
SQL;

    const DQL_RECENT_PUSH = <<<SQL
  SELECT p
    FROM QL\Hal\Core\Entity\Push p
   WHERE p.deployment = :deploy
ORDER BY p.created DESC
SQL;

    const DQL_RECENT_SUCCESSFUL_PUSH = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
    WHERE
        p.deployment = :deploy AND
        p.status = :status
 ORDER BY p.created DESC
SQL;

    /**
     * Get all pushes that can be used as a rollback
     *
     * @param Deployment $deployment
     *
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getAvailableRollbacksByDeployment(Deployment $deployment, $limit = 25, $page = 0)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_ROLLBACKS)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page)
            ->setParameter('deployment', $deployment)
            ->setParameter('pushStatus', 'Success')
            ->setParameter('buildStatus', 'Success');

        return new Paginator($query);
    }

    /**
     * Get all pushes for a deployment
     *
     * @param Deployment $deployment
     *
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getByDeployment(Deployment $deployment, $limit = 25, $page = 0)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_BY_DEPLOYMENT)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page)
            ->setParameter('deployment', $deployment);

        return new Paginator($query);
    }

    /**
     * Get all pushes for a application
     *
     * @param Application $application
     * @param int $limit
     * @param int $page
     *
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

    /**
     * Get the most recent push for a deployment
     *
     * @param Deployment $deployment
     *
     * @return Push|null
     */
    public function getMostRecentByDeployment(Deployment $deployment)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_RECENT_PUSH)
            ->setMaxResults(1)
            ->setParameter('deploy', $deployment);

        return $query->getOneOrNullResult();
    }

    /**
     * Get the most recent successful push for a deployment
     *
     * @param Deployment $deployment
     *
     * @return Push|null
     */
    public function getMostRecentSuccessByDeployment(Deployment $deployment)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_RECENT_SUCCESSFUL_PUSH)
            ->setMaxResults(1)
            ->setParameter('deploy', $deployment)
            ->setParameter('status', 'Success');

        return $query->getOneOrNullResult();
    }
}
