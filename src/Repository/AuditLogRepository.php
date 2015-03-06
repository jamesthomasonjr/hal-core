<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AuditLogRepository extends EntityRepository
{
    const DQL_GET_PAGED = <<<SQL
  SELECT l
    FROM QL\Hal\Core\Entity\AuditLog l
ORDER BY l.recorded DESC
SQL;

    /**
     * Get all audit logs, paged
     *
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagedResults($limit = 25, $page = 0)
    {
        $query = $this->getEntityManager()
            ->createQuery(self::DQL_GET_PAGED)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page);

        return new Paginator($query);
    }
}
