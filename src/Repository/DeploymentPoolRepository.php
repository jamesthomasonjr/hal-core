<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
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

    /**
     * Get all a deployment pool by View and Deployment. Only really used for dupe checking when saving a relation.
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
}
