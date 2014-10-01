<?php
# src/QL/Hal/Core/Entity/Type/TimePointType.php

namespace QL\Hal\Core\Entity\Type;

use Doctrine\DBAL\Types\Type as BaseType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use MCP\DataType\Time\TimePoint;
use DateTime;
use DateTimeZone;

/**
 *  Doctrine TimePoint Type
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @author Steve Kluck <stevekluck@quickenloans.com>
 */
class TimePointType extends BaseType
{
    const TYPE = 'timepoint';

    /**
     *  Convert TimePoint to database value
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return null|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof TimePoint) {
            return $value->format($platform->getDateTimeFormatString(), 'UTC');
        }

        return null;
    }

    /**
     *  Convert database value to TimePoint
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|void
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!$value) {
            return null;
        }

        if (!$date = DateTime::createFromFormat('Y-m-d H:i:s', $value, new DateTimeZone('UTC'))) {
            return null;
        }

        return new TimePoint(
            $date->format('Y'),
            $date->format('m'),
            $date->format('d'),
            $date->format('H'),
            $date->format('i'),
            $date->format('s'),
            'UTC'
        );
    }

    /**
     *  Get the type name
     *
     *  @return string
     */
    public function getName()
    {
        return self::TYPE;
    }

    /**
     *  Get the Timepoint field declaration
     *
     *  @param array $fieldDeclaration
     *  @param AbstractPlatform $platform
     *  @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getTimeTypeDeclarationSQL([]);
    }
}
