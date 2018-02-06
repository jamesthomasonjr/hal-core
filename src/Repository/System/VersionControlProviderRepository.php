<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository\System;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Hal\Core\Entity\Application;
use Hal\Core\Entity\System\VersionControlProvider;
use Hal\Core\Utility\PagedResultsTrait;

class VersionControlProviderRepository extends EntityRepository
{
    use PagedResultsTrait;

    const DQL_GET_VCSS = <<<SQL_QUERY
   SELECT vcs
     FROM %s vcs
 ORDER BY vcs.name
SQL_QUERY;

    const DQL_APP_COUNT_FOR_VCS = <<<SQL_QUERY
   SELECT p.id, COUNT(p.id) as applications
     FROM %s a
     JOIN %s p WITH p = a.provider
 GROUP BY p.id
SQL_QUERY;

    /**
     * @return array
     */
    public function getApplicationCounts(): array
    {
        $dql = sprintf(self::DQL_APP_COUNT_FOR_VCS, Application::class, VersionControlProvider::class);

        $result = $this->getEntityManager()
            ->createQuery($dql)
            ->getResult();

        $counts = [];
        foreach ($result as ['id' => $id, 'applications' => $applications]) {
            $counts[$id] = $applications;
        }

        return $counts;
    }

    /**
     * Get all VCSs, paged.
     *
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagedResults($limit = 50, $page = 0)
    {
        $dql = sprintf(self::DQL_GET_VCSS, VersionControlProvider::class);

        return $this->getPaginator($dql, $limit, $page);
    }
}
