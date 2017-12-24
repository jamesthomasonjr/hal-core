<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * This trait expects the parent to provide "$this->getEntityManager()".
 *
 * It should only be used by EntityRepositories.
 */
trait PagedResultsTrait
{
    /**
     * @param string $dql
     * @param int $limit
     * @param int $page
     * @param array $parameters
     *
     * @return Paginator
     */
    private function getPaginator($dql, $limit, $page, array $parameters = [])
    {
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page);

        foreach ($parameters as $param => $value) {
            $query = $query->setParameter($param, $value);
        }

        return new Paginator($query);
    }
}
