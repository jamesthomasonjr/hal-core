<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use QL\Hal\Core\Entity\Application;
use QL\Hal\Core\Entity\Environment;
use QL\Hal\Core\Utility\SortingTrait;

class EnvironmentRepository extends EntityRepository
{
    use SortingTrait;

    const ENV_QUERY_REGION = 'environment_region_appId_%s';

    const DQL_BY_REPOSITORY = <<<SQL
   SELECT e
     FROM QL\Hal\Core\Entity\Deployment d

     JOIN QL\Hal\Core\Entity\Server s WITH s = d.server
     JOIN QL\Hal\Core\Entity\Environment e WITH e = s.environment

    WHERE d.application = :application
SQL;

    /**
     * Get all buildable environments for a application
     *
     * @param Application $application
     *
     * @return Environment[]
     */
    public function getBuildableEnvironmentsByApplication(Application $application)
    {
        $regionId = sprintf(self::ENV_QUERY_REGION, $application->id());

        $query = $this->getEntityManager()
            ->createQuery(self::DQL_BY_REPOSITORY)
            ->setCacheable(true)
            ->setCacheRegion($regionId)
            ->setParameter('application', $application);

        $environments = $query->getResult();

        usort($environments, $this->environmentSorter());

        return $environments;
    }

    /**
     * Get all buildable environments for a application
     *
     * @param Application $application
     *
     * @return void
     */
    public function clearBuildableEnvironmentsByApplication(Application $application)
    {
        $regionId = sprintf(self::ENV_QUERY_REGION, $application->id());

        $cache = $this->getEntityManager()->getCache();
        $envQueryCache = $cache->getQueryCache($regionId);
        $envQueryCache->clear();
    }

    /**
     * @param callable $sorter
     *
     * @return Environment[]
     */
    public function getAllEnvironmentsSorted(callable $sorter = null)
    {
        $environments = $this->findAll();

        if ($sorter) {
            usort($environments, $sorter);
        } else {
            usort($environments, $this->environmentSorter());
        }

        return $environments;
    }
}
