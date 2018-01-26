<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository\System;

use Doctrine\ORM\EntityRepository;
use Hal\Core\Entity\User;
use Hal\Core\Entity\System\UserIdentityProvider;

class UserIdentityProviderRepository extends EntityRepository
{
    const DQL_USER_COUNT_FOR_IDP = <<<SQL_QUERY
   SELECT p.id, COUNT(p.id) as users
     FROM %s u
     JOIN %s p WITH p = u.provider
 GROUP BY p.id
SQL_QUERY;

    /**
     * @return array
     */
    public function getUserCounts(): array
    {
        $dql = sprintf(self::DQL_USER_COUNT_FOR_IDP, User::class, UserIdentityProvider::class);

        $result = $this->getEntityManager()
            ->createQuery($dql)
            ->getResult();

        $counts = [];
        foreach ($result as ['id' => $id, 'users' => $users]) {
            $counts[$id] = $users;
        }

        return $counts;
    }
}
