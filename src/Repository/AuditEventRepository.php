<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Hal\Core\Entity\AuditEvent;
use Hal\Core\Utility\PagedResultsTrait;

class AuditEventRepository extends EntityRepository
{
    use PagedResultsTrait;

    const DQL_GET_PAGED = <<<SQL
  SELECT event
    FROM %s event
ORDER BY event.created DESC
SQL;

    /**
     * Get all audit events, paged.
     *
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagedResults($limit = 50, $page = 0)
    {
        $dql = sprintf(self::DQL_GET_PAGED, AuditEvent::class);
        return $this->getPaginator($dql, $limit, $page);
    }
}
