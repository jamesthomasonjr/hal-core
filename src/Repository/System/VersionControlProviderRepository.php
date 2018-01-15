<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository\System;

use Doctrine\ORM\EntityRepository;
use Hal\Core\Entity\Application;
use Hal\Core\Entity\System\VersionControlProvider;

class VersionControlProviderRepository extends EntityRepository
{
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
}
