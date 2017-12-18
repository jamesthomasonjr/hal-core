<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Hal\Core\Entity\Application;
use Hal\Core\Entity\Organization;
use Hal\Core\Utility\PagedResultsTrait;
use Hal\Core\Utility\SortingTrait;

class ApplicationRepository extends EntityRepository
{
    use PagedResultsTrait;
    use SortingTrait;

    const DQL_ALL = <<<SQL
   SELECT application
     FROM %s application
 ORDER BY application.name ASC
SQL;

    /**
     * Get all applications sorted into organizations.
     *
     * @return array
     */
    public function getGroupedApplications()
    {
        $organizations = $this->getEntityManager()
            ->getRepository(Organization::class)
            ->findAll();

        $applications = $this->findAll();

        usort($applications, $this->applicationSorter());
        usort($organizations, $this->organizationSorter());

        $grouped = [];
        foreach ($organizations as $org) {
            $grouped[$org->id()] = [];
        }

        foreach ($applications as $app) {
            $orgID = $app->organization() ? $app->organization()->id() : 'none';
            $grouped[$orgID][] = $app;
        }

        return $grouped;
    }

    /**
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagedResults($limit = 25, $page = 0)
    {
        $template = self::DQL_ALL;
        $dql = sprintf($template, Application::class);

        return $this->getPaginator($dql, $limit, $page, []);
    }
}
