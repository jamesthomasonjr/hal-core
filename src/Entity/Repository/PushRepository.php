<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Push;
use QL\Hal\Core\Entity\Repository;
use QL\Hal\Core\Entity\Server;

class PushRepository extends EntityRepository
{
    const DQL_ROLLBACKS = <<<SQL
SELECT p
  FROM QL\Hal\Core\Entity\Push p
  JOIN p.deployment d
  JOIN p.build b
 WHERE
    d.server = :server AND
    d.repository = :repo AND
    p.status = :pushStatus AND
    b.status = :buildStatus
 ORDER BY p.end DESC
SQL;

    const DQL_BY_REPOSITORY = <<<SQL
SELECT p
  FROM QL\Hal\Core\Entity\Push p
  JOIN p.deployment d
 WHERE
    d.repository = :repo
 ORDER BY p.end DESC
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
     * @return array
     */
    public function getAvailableRollbacks(Repository $repository, Server $server, $limit = 25)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_ROLLBACKS)
            ->setMaxResults($limit)
            ->setParameter('repo', $repository)
            ->setParameter('server', $server)
            ->setParameter('pushStatus', 'Success')
            ->setParameter('buildStatus', 'Success');

        return $query->getResult();
    }

    /**
     * Get all pushes for a repository
     *
     * @param Repository $repository
     * @return array
     */
    public function getForRepository(Repository $repository)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_BY_REPOSITORY)
            ->setParameter('repo', $repository);

        return $query->getResult();
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
