<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Push;
use QL\Hal\Core\Entity\Repository;
use QL\Hal\Core\Entity\Server;

class PushRepository extends EntityRepository
{
    const REGEX_COMMIT = '#^[0-9a-f]{40}$#i';

    const DQL_ROLLBACKS = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
     JOIN p.deployment d
     JOIN p.build b
    WHERE
        d.server = :server AND
        p.repository = :repo AND
        p.status = :pushStatus AND
        b.status = :buildStatus
 ORDER BY p.created DESC
SQL;

    const DQL_BY_REPOSITORY = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
    WHERE p.repository = :repo
 ORDER BY p.created DESC
SQL;
    const DQL_BY_REPOSITORY_WITH_REF_FILTER = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
     JOIN QL\Hal\Core\Entity\Build b WITH b = p.build
    WHERE p.repository = :repo
      AND b.branch = :ref
 ORDER BY p.created DESC
SQL;
    const DQL_BY_REPOSITORY_WITH_SHA_FILTER = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\Push p
     JOIN QL\Hal\Core\Entity\Build b WITH b = p.build
    WHERE p.repository = :repo
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
     * Get all pushes, with available builds, that can be used as a rollback
     *
     * @param Repository $repository
     * @param Server $server
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getAvailableRollbacks(Repository $repository, Server $server, $limit = 25, $page = 0)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_ROLLBACKS)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page)
            ->setParameter('repo', $repository)
            ->setParameter('server', $server)
            ->setParameter('pushStatus', 'Success')
            ->setParameter('buildStatus', 'Success');

        return new Paginator($query);
    }

    /**
     * Get all pushes for a repository
     *
     * @param Repository $repository
     * @param int $limit
     * @param int $page
     *
     * @param string|null $filter
     *
     * @return Paginator
     */
    public function getForRepository(Repository $repository, $limit = 25, $page = 0, $filter = null)
    {
        $dql = self::DQL_BY_REPOSITORY;
        if ($filter) {
            $dql = self::DQL_BY_REPOSITORY_WITH_REF_FILTER;

            // is a commit sha
            if (preg_match(self::REGEX_COMMIT, $filter) === 1) {
                $dql = self::DQL_BY_REPOSITORY_WITH_SHA_FILTER;
                $filter = strtolower($filter);
            }
        }

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page)
            ->setParameter('repo', $repository);

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
