<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use QL\Hal\Core\Entity\Group;
use QL\Hal\Core\Utility\SortingTrait;

class ApplicationRepository extends EntityRepository
{
    use SortingTrait;

    /**
     * Get all applications sorted into groups.
     *
     * @return array
     */
    public function getGroupedApplications()
    {
        $groups = $this->getEntityManager()
            ->getRepository(Group::CLASS)
            ->findAll();

        $applications = $this->findAll();

        usort($applications, $this->applicationSorter());
        usort($groups, $this->groupSorter());

        $grouped = [];
        foreach ($groups as $group) {
            $grouped[$group->id()] = [];
        }

        foreach ($applications as $app) {
            $grouped[$app->group()->id()][] = $app;
        }

        return $grouped;
    }
}
