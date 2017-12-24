<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Hal\Core\Entity\Group;
use Hal\Core\Utility\PagedResultsTrait;

class GroupRepository extends EntityRepository
{
    use PagedResultsTrait;

    const DQL_GET_PAGED = <<<SQL
   SELECT grp
     FROM %s grp
 ORDER BY grp.type, grp.name DESC
SQL;

    /**
     * Get all groups, paged.
     *
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagedResults($limit = 50, $page = 0)
    {
        $dql = sprintf(self::DQL_GET_PAGED, Group::class);
        return $this->getPaginator($dql, $limit, $page);
    }
}
