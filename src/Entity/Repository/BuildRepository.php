<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use QL\Hal\Core\Entity\Repository;

class BuildRepository extends EntityRepository
{
    const DQL_BY_REPOSITORY = <<<SQL
   SELECT b
     FROM QL\Hal\Core\Entity\Build b
    WHERE b.repository = :repo
 ORDER BY b.created DESC
SQL;

    /**
     * Get all builds for a repository
     *
     * @param Repository $repository
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getForRepository(Repository $repository, $limit = 25, $page = 0)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_BY_REPOSITORY)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page)
            ->setParameter('repo', $repository);

        return new Paginator($query);
    }
}
