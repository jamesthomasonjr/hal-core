<?php
# src/QL/Hal/Core/Entity/Type/HttpUrlType.php

namespace QL\Hal\Core\Entity\Type;

use Doctrine\DBAL\Types\Type as BaseType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use MCP\DataType\HttpUrl;

/**
 *  Doctrine HTTP Url Type
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 *  @author Steve Kluck <stevekluck@quickenloans.com>
 */
class HttpUrlType extends BaseType
{
    const TYPE = 'url';

    /**
     *  Convert HttpUrl to database value
     *
     *  @param mixed $value
     *  @param AbstractPlatform $platform
     *  @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof HttpUrl) {
            return $value->asString();
        }

        return null;
    }

    /**
     *  Convert database value to HttpUrl
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return HttpUrl|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value) {
            return HttpUrl::create($value);
        }

        return null;
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
     *  Get the HttpUrl field definition
     *
     *  @param array $fieldDeclaration
     *  @param AbstractPlatform $platform
     *  @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL([
            'fixed'  => true,
            'length' => '128'
        ]);
    }
}
