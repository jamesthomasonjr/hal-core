<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository\User;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Hal\Core\Entity\User;
use Hal\Core\Entity\User\UserPermission;
use Hal\Core\Utility\PagedResultsTrait;

class UserPermissionRepository extends EntityRepository
{
    use PagedResultsTrait;

    const DQL_ALL = <<<SQL_QUERY
   SELECT p
     FROM %s p
     JOIN %s u WITH u = p.user

 ORDER BY u.name ASC
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
        $dql = sprintf($template, UserPermission::class, User::class);

        return $this->getPaginator($dql, $limit, $page, []);
    }
}
