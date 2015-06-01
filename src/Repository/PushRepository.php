<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Build;
use QL\Hal\Core\Entity\Deployment;
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

    const DQL_RECENT_PUSH = <<<SQL
  SELECT p
    FROM QL\Hal\Core\Entity\Push p
   WHERE p.deployment = :deploy
ORDER BY p.created DESC
SQL;

    const DQL_RECENT_PUSHES = <<<SQL
  SELECT *
    FROM Pushes p1
   WHERE p1.PushId = (
        SELECT p2.PushId
          FROM Pushes p2
         WHERE p2.DeploymentId = p1.DeploymentId
           AND p2.DeploymentId IN (:deployments)
         ORDER BY p2.PushCreated DESC
         LIMIT 1
        )
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
     * WARNING! This query uses native SQL and may not be compatible with non-mysql databases!
     *
     * Get the most recent push for a list of deployments
     *
     * @param Deployment[] $deployments
     *
     * @return Push[]
     */
    public function getMostRecentByDeployments(array $deployments)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Push::CLASS, 'p');
        $rsm->addFieldResult('p', 'PushId', 'id');

        $ids = [];
        foreach ($deployments as $deployment) {
            $ids[] = $deployment->id();
        }

        $query = $this->getEntityManager()
            ->createNativeQuery(self::DQL_RECENT_PUSHES, $rsm)
            ->setParameter('deployments', $ids);

        $ids = [];
        foreach ($query->getScalarResult() as $push) {
            $ids[] = array_shift($push);
        }

        $pushes = $this->findBy(['id' => $ids]);

        $latest = [];
        foreach ($pushes as $push) {
            if (!$push->getDeployment()) {
                continue;
            }

            $latest[$push->getDeployment()->id()] = $push;
        }

        return $latest;
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
