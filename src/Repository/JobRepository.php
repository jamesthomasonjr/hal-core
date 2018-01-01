<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Hal\Core\Entity\Job;
use Hal\Core\Utility\PagedResultsTrait;

class JobRepository extends EntityRepository
{
    use PagedResultsTrait;

    const DQL_ALL = <<<SQL_QUERY
   SELECT j
     FROM %s j
 ORDER BY j.created DESC
SQL_QUERY;

    /**
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagedResults($limit = 25, $page = 0)
    {
        $template = self::DQL_ALL;
        $dql = sprintf($template, Job::class);

        return $this->getPaginator($dql, $limit, $page, []);
    }
}
