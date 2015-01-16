<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Environment;
use QL\Hal\Core\Entity\Repository;

class DeploymentRepository extends EntityRepository
{
    const DQL_BY_REPOSITORY_AND_ENVIRONMENT = <<<SQL
   SELECT d
     FROM QL\Hal\Core\Entity\Deployment d

     JOIN QL\Hal\Core\Entity\Server s WITH s = d.server
     JOIN QL\Hal\Core\Entity\Environment e WITH e = s.environment

    WHERE d.repository = :repo
      AND s.environment = :env
SQL;

    /**
     * Get all buildable environments for a repository
     *
     * @param Repository $repository
     * @param Environment $environment
     *
     * @return Deployment[]
     */
    public function getDeploymentsForRepositoryEnvironment(Repository $repository, Environment $environment)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_BY_REPOSITORY_AND_ENVIRONMENT)
            ->setParameter('repo', $repository)
            ->setParameter('env', $environment);

        return $query->getResult();
    }
}
