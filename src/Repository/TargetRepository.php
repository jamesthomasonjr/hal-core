<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Hal\Core\Entity\Application;
use Hal\Core\Entity\Build;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Group;
use Hal\Core\Entity\Target;

class TargetRepository extends EntityRepository
{
    const DQL_BY_APP_AND_ENVIRONMENT = <<<SQL
   SELECT target
     FROM %s target

     JOIN %s grp WITH grp = target.group
     JOIN %s env WITH env = grp.environment

    WHERE target.application = :application
      AND grp.environment = :env
SQL;

    /**
     * Get all targets for an application and environment.
     *
     * @param Application $application
     * @param Environment $environment
     *
     * @return Target[]
     */
    public function getByApplicationAndEnvironment(Application $application, Environment $environment)
    {
        $dql = sprintf(self::DQL_BY_APP_AND_ENVIRONMENT, Target::class, Group::class, Environment::class);
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('application', $application)
            ->setParameter('env', $environment);

        return $query->getResult();
    }
}
