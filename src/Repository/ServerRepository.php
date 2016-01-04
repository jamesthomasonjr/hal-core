<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ServerRepository extends EntityRepository
{
    const DQL_GET_SERVERS = <<<SQL
   SELECT s
     FROM QL\Hal\Core\Entity\Server s
 ORDER BY s.type, s.name DESC
SQL;

    /**
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getPaginatedServers($limit = 25, $page = 0)
    {
        $dql = self::DQL_GET_SERVERS;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page);

        return new Paginator($query);
    }
}
