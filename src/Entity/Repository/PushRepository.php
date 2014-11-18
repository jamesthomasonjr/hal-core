<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use QL\Hal\Core\Entity\Server;
use QL\Hal\Core\Entity\Repository;

class PushRepository extends EntityRepository
{
    /**
     * Get all pushes, with available builds, that can be used as a rollback
     *
     * @param Repository $repository
     * @param Server $server
     * @param int $limit
     * @return array
     */
    public function getAvailableRollbacks(Repository $repository, Server $server, $limit = 25)
    {
        $dql = 'SELECT p FROM QL\Hal\Core\Entity\Push p
            JOIN p.deployment d JOIN p.build b
            WHERE d.server = :server AND d.repository = :repo AND p.status = :status AND b.status = :buildstatus
            ORDER BY p.end DESC';
        $query = $this->_em->createQuery($dql)
                          ->setMaxResults($limit)
                          ->setParameter('repo', $repository)
                          ->setParameter('server', $server)
                          ->setParameter('status', 'Success')
                          ->setParameter('buildstatus', 'Success');
        return $query->getResult();
    }

    /**
     * Get all pushes for a repository
     *
     * @param Repository $repository
     * @return array
     */
    public function getForRepository(Repository $repository)
    {
        $dql = 'SELECT p FROM QL\Hal\Core\Entity\Push p JOIN p.deployment d WHERE d.repository = :repo ORDER BY p.end DESC';
        $query = $this->_em->createQuery($dql)
            ->setParameter('repo', $repository);
        return $query->getResult();
    }
}
