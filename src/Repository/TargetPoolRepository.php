<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Hal\Core\Entity\Target;
use Hal\Core\Entity\TargetPool;
use Hal\Core\Entity\TargetView;

class TargetPoolRepository extends EntityRepository
{
    const DQL_BY_VIEW_AND_TARGET = <<<SQL
   SELECT pool
     FROM %s view
     JOIN %s pool WITH view = pool.view
    WHERE view = :view
      AND :target MEMBER OF pool.targets
SQL;

    /**
     * Get a target pool by View and Target. Only used for dupe checking when saving a relation.
     *
     * @param TargetView $view
     * @param Target $target
     *
     * @return DeploymentPool[]
     */
    public function getPoolForViewAndDeployment(TargetView $view, Target $target)
    {
        $dql = sprintf(self::DQL_BY_VIEW_AND_TARGET, TargetView::class, TargetPool::class);
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('view', $view)
            ->setParameter('target', $target);

        return $query->getResult();
    }
}
