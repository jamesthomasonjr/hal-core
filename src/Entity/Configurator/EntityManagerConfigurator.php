<?php
# src/QL/Hal/Core/Entity/Configurator/EntityManagerConfigurator.php

namespace QL\Hal\Core\Entity\Configurator;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use QL\Hal\Core\Entity\Type\BuildStatusEnumType;
use QL\Hal\Core\Entity\Type\EventEnumType;
use QL\Hal\Core\Entity\Type\HttpUrlType;
use QL\Hal\Core\Entity\Type\PushStatusEnumType;
use QL\Hal\Core\Entity\Type\TimePointType;

/**
 *  Perform runtime configuration of the Doctrine Entity Manager
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @author Steve Kluck <stevekluck@quickenloans.com>
 */
class EntityManagerConfigurator
{
    /**
     *  Run the configuration
     *
     *  @param EntityManagerInterface $em
     */
    public function configure(EntityManagerInterface $em)
    {
        Type::addType(HttpUrlType::TYPE, 'QL\Hal\Core\Entity\Type\HttpUrlType');
        Type::addType(TimePointType::TYPE, 'QL\Hal\Core\Entity\Type\TimePointType');
        Type::addType(BuildStatusEnumType::TYPE, 'QL\Hal\Core\Entity\Type\BuildStatusEnumType');
        Type::addType(PushStatusEnumType::TYPE, 'QL\Hal\Core\Entity\Type\PushStatusEnumType');
        Type::addType(EventEnumType::TYPE, 'QL\Hal\Core\Entity\Type\EventEnumType');

        $platform = $em->getConnection()->getDatabasePlatform();

        $platform->registerDoctrineTypeMapping(sprintf('db_%s', HttpUrlType::TYPE), HttpUrlType::TYPE);
        $platform->registerDoctrineTypeMapping(sprintf('db_%s', TimePointType::TYPE), TimePointType::TYPE);
        $platform->registerDoctrineTypeMapping(sprintf('db_%s', BuildStatusEnumType::TYPE), BuildStatusEnumType::TYPE);
        $platform->registerDoctrineTypeMapping(sprintf('db_%s', PushStatusEnumType::TYPE), PushStatusEnumType::TYPE);
        $platform->registerDoctrineTypeMapping(sprintf('db_%s', EventEnumType::TYPE), EventEnumType::TYPE);
    }
}
