<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Hal\Core\Entity\TargetTemplate;
use Hal\Core\Utility\PagedResultsTrait;

class TargetTemplateRepository extends EntityRepository
{
    use PagedResultsTrait;

    const DQL_GET_PAGED = <<<SQL_QUERY
   SELECT t
     FROM %s t
 ORDER BY t.type, t.name DESC
SQL_QUERY;

    /**
     * Get all templates, paged.
     *
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagedResults($limit = 50, $page = 0)
    {
        $dql = sprintf(self::DQL_GET_PAGED, TargetTemplate::class);
        return $this->getPaginator($dql, $limit, $page);
    }
}
