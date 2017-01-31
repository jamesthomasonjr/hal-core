<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Hal\Core\Entity\Organization;
use Hal\Core\Utility\SortingTrait;

class ApplicationRepository extends EntityRepository
{
    use SortingTrait;

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
            $grouped[$app->organization()->id()][] = $app;
        }

        return $grouped;
    }
}
