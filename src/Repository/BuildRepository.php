<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use QL\Hal\Core\Entity\Application;

class BuildRepository extends EntityRepository
{
    const REGEX_COMMIT = '#^[0-9a-f]{40}$#i';

    const DQL_BY_REPOSITORY = <<<SQL
   SELECT b
     FROM QL\Hal\Core\Entity\Build b
    WHERE b.application = :application
 ORDER BY b.created DESC
SQL;
    const DQL_BY_REPOSITORY_WITH_REF_FILTER = <<<SQL
   SELECT b
     FROM QL\Hal\Core\Entity\Build b
    WHERE b.application = :application
      AND b.branch = :ref
 ORDER BY b.created DESC
SQL;
    const DQL_BY_REPOSITORY_WITH_SHA_FILTER = <<<SQL
   SELECT b
     FROM QL\Hal\Core\Entity\Build b
    WHERE b.application = :application
      AND b.commit = :ref
 ORDER BY b.created DESC
SQL;

    /**
     * Get all builds for a application
     *
     * @param Application $application
     * @param int $limit
     * @param int $page
     * @param string|null $filter
     *
     * @return Paginator
     */
    public function getByApplication(Application $application, $limit = 25, $page = 0, $filter = null)
    {
        $dql = self::DQL_BY_REPOSITORY;
        if ($filter) {
            $dql = self::DQL_BY_REPOSITORY_WITH_REF_FILTER;

            // is a commit sha
            if (preg_match(self::REGEX_COMMIT, $filter) === 1) {
                $dql = self::DQL_BY_REPOSITORY_WITH_SHA_FILTER;
                $filter = strtolower($filter);
            }
        }

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page)
            ->setParameter('application', $application);

        if ($filter) {
            $query->setParameter('ref', $filter);
        }

        return new Paginator($query);
    }
}
