<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use QL\Hal\Core\DoctrinePagination\Mysql57Paginator as Paginator;

class AuditLogRepository extends EntityRepository
{
    const DQL_GET_PAGED = <<<SQL
  SELECT l
    FROM QL\Hal\Core\Entity\AuditLog l
ORDER BY l.created DESC
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
