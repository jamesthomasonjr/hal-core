<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Environment;

class DeploymentRepository extends EntityRepository
{
    const DQL_BY_REPOSITORY_AND_ENVIRONMENT = <<<SQL
   SELECT d
     FROM QL\Hal\Core\Entity\Deployment d

     JOIN QL\Hal\Core\Entity\Server s WITH s = d.server
     JOIN QL\Hal\Core\Entity\Environment e WITH e = s.environment

    WHERE d.application = :application
      AND s.environment = :env
SQL;

    /**
     * Get all deployments for a application and environment
     *
     * @param Application $application
     * @param Environment $environment
     *
     * @return Deployment[]
     */
    public function getDeploymentsByApplicationEnvironment(Application $application, Environment $environment)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_BY_REPOSITORY_AND_ENVIRONMENT)
            ->setParameter('application', $application)
            ->setParameter('env', $environment);

        return $query->getResult();
    }
}
