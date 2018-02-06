<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Hal\Core\Entity\Application;
use Hal\Core\Entity\Environment;

class EncryptedPropertyRepository extends EntityRepository
{
    /**
     * @param Application $application
     * @param Environment|null $environment
     *
     * @return array
     */
    public function getPropertiesForEnvironment(Application $application, ?Environment $environment): array
    {
        $environmentCriteria = Criteria::expr()->isNull('environment');

        if ($environment) {
            $specificEnvironment = Criteria::expr()->eq('environment', $environment);
            $environmentCriteria = Criteria::expr()->orX($specificEnvironment, $environmentCriteria);
        }

        $criteria = (new Criteria)
            ->where(Criteria::expr()->eq('application', $application))
            ->andWhere($environmentCriteria)

            // null must be first!
            ->orderBy(['environment' => 'ASC']);

        $properties = $this
            ->matching($criteria)
            ->toArray();


        $config = [];

        // We do this so that "global/no-env" properties are loaded first, and can be overwritten by environment-specific config.
        foreach ($properties as $property) {
            $config[$property->name()] = $property;
        }

        return $config;
    }
}
