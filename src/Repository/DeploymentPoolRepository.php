<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\DeploymentPool;
use QL\Hal\Core\Entity\DeploymentView;

class DeploymentPoolRepository extends EntityRepository
{
    const DQL_BY_VIEW_AND_DEPLOYMENT = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\DeploymentView v
     JOIN QL\Hal\Core\Entity\DeploymentPool p WITH v = p.view
    WHERE v = :view
      AND :deployment MEMBER OF p.deployments
SQL;

    const DQL_BY_DEPLOYMENT = <<<SQL
   SELECT p
     FROM QL\Hal\Core\Entity\DeploymentPool p
    WHERE :deployment MEMBER OF p.deployments
SQL;

    /**
     * Get all a deployment pool by View and deployment
     *
     * @param DeploymentView $view
     * @param Deployment $deployment
     *
     * @return DeploymentPool[]
     */
    public function getPoolForViewAndDeployment(DeploymentView $view, Deployment $deployment)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_BY_VIEW_AND_DEPLOYMENT)
            ->setParameter('view', $view)
            ->setParameter('deployment', $deployment);

        return $query->getResult();
    }

    /**
     * Get all deployment pool by deployment
     *
     * @param DeploymentView $view
     * @param Deployment $deployment
     *
     * @return DeploymentPool|null
     */
    public function getPoolForDeployment(Deployment $deployment)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_BY_DEPLOYMENT)
            ->setParameter('deployment', $deployment);

        return $query->getOneOrNullResult();
    }
}
