<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use QL\Hal\Core\Entity\User;

class UserRepository extends EntityRepository
{
    const DQL_BUILD_COUNT = <<<SQL
   SELECT count(p)
     FROM QL\Hal\Core\Entity\Build b
    WHERE
        b.user = :user
SQL;

    const DQL_PUSH_COUNT = <<<SQL
   SELECT count(p)
     FROM QL\Hal\Core\Entity\Push p
    WHERE
        p.user = :user
SQL;

    /**
     * Get all number of builds for User
     *
     * @param User $user
     *
     * @return int
     */
    public function getBuildCount(User $user)
    {
        $dql = self::DQL_BUILD_COUNT;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }

    /**
     * Get all number of pushes for User
     *
     * @param User $user
     *
     * @return int
     */
    public function getPushCount(User $user)
    {
        $dql = self::DQL_PUSH_COUNT;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }
}
