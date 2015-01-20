<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use QL\Hal\Core\Entity\Environment;
use QL\Hal\Core\Entity\Repository;

class EnvironmentRepository extends EntityRepository
{
    const DQL_BY_REPOSITORY = <<<SQL
   SELECT e
     FROM QL\Hal\Core\Entity\Deployment d

     JOIN QL\Hal\Core\Entity\Server s WITH s = d.server
     JOIN QL\Hal\Core\Entity\Environment e WITH e = s.environment

    WHERE d.repository = :repo
 ORDER BY e.order ASC
SQL;

    /**
     * Get all buildable environments for a repository
     *
     * @param Repository $repository
     *
     * @return Environment[]
     */
    public function getBuildableEnvironmentsByRepository(Repository $repository)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_BY_REPOSITORY)
            ->setParameter('repo', $repository);

        return $query->getResult();
    }
}
