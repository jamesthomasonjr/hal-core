<?php
# src/QL/Hal/Core/Entity/Repository/DeploymentRepository.php

namespace QL\Hal\Core\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use QL\Hal\Core\Entity\Repository;
use QL\Hal\Core\Entity\Deployment;
use QL\Hal\Core\Entity\Push;
use QL\Hal\Core\Entity\Environment;

/**
 *  Deployment Repository
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 */
class DeploymentRepository extends EntityRepository
{
    /**
     * Get the status of a deployment in the following format
     *
     * array(
     *     'latest' => Push|null,
     *     'success' => Push|null
     * )
     *
     * @param Deployment $deployment
     * @return array
     */
    public function getStatus(Deployment $deployment)
    {
        return [
            'latest' => $this->getLastPush($deployment),
            'success' => $this->getLastSuccessfulPush($deployment)
        ];
    }

    /**
     * Get the last attempted push for a deployment
     *
     * @param Deployment $deployment
     * @return Push|null
     */
    public function getLastPush(Deployment $deployment)
    {
        $dql = 'SELECT p FROM QL\Hal\Core\Entity\Push p WHERE p.deployment = :deploy ORDER BY p.id DESC';
        $query = $this->_em->createQuery($dql)
            ->setMaxResults(1)
            ->setParameter('deploy', $deployment);
        return $query->getOneOrNullResult();
    }

    /**
     * Get the last successful push for a deployment
     *
     * @param Deployment $deployment
     * @return Push|null
     */
    public function getLastSuccessfulPush(Deployment $deployment)
    {
        $dql = 'SELECT p FROM QL\Hal\Core\Entity\Push p WHERE p.deployment = :deploy AND p.status = :status ORDER BY p.end DESC';
        $query = $this->_em->createQuery($dql)
            ->setMaxResults(1)
            ->setParameter('deploy', $deployment)
            ->setParameter('status', 'Success');
        return $query->getOneOrNullResult();
    }

    /**
     * Get all deployments for a given repository and environment
     *
     * @param Repository $repository
     * @param Environment $environment
     * @return array
     */
    public function getDeploymentsForEnvironment(Repository $repository, Environment $environment)
    {
        $dql = "SELECT d FROM QL\Hal\Core\Entity\Deployment d JOIN d.server s WHERE d.repository = :repo AND s.environment = :env";
        $query = $this->_em->createQuery($dql)
            ->setParameter('repo', $repository)
            ->setParameter('env', $environment);
        return $query->getResult();
    }
}
