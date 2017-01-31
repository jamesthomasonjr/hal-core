<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Hal\Core\Entity\User;
use Hal\Core\Utility\PagedResultsTrait;

class UserRepository extends EntityRepository
{
    use PagedResultsTrait;

    const DQL_GET_USERS = <<<SQL
   SELECT user
     FROM %s user
 ORDER BY user.username ASC
SQL;

    /**
     * Get all users, paged.
     *
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagedResults($limit = 50, $page = 0)
    {
        $dql = sprintf(self::DQL_GET_USERS, User::class);

        return $this->getPaginator($dql, $limit, $page);
    }
}
