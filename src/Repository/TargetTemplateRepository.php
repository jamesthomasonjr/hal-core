<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\TargetTemplate;
use Hal\Core\Utility\PagedResultsTrait;
use Hal\Core\Utility\SortingTrait;

class TargetTemplateRepository extends EntityRepository
{
    use PagedResultsTrait;
    use SortingTrait;

    const DQL_GET_PAGED = <<<SQL_QUERY
   SELECT t
     FROM %s t
 ORDER BY t.type, t.name DESC
SQL_QUERY;

    /**
     * Get all templates, paged.
     *
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagedResults($limit = 50, $page = 0)
    {
        $dql = sprintf(self::DQL_GET_PAGED, TargetTemplate::class);
        return $this->getPaginator($dql, $limit, $page);
    }

    /**
     * Get all templates sorted into environments.
     *
     * @return array
     */
    public function getGroupedTemplates()
    {
        $environments = $this->getEntityManager()
            ->getRepository(Environment::class)
            ->findAll();

        $templates = $this->findAll();

        usort($environments, $this->environmentSorter());
        usort($templates, $this->templateSorter());

        $grouped = [];
        foreach ($environments as $env) {
            $grouped[$env->id()] = [];
        }

        foreach ($templates as $template) {
            $envID = $template->environment() ? $template->environment()->id() : 'none';
            $grouped[$envID][] = $template;
        }

        return $grouped;
    }
}
