<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Type;

use Doctrine\DBAL\Types\Type as BaseType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Compressed Blob Type
 */
class CompressedSerializedBlobType extends BaseType
{
    const TYPE = 'compressedserialized';

    /**
     * Convert plain text to database value
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $serialized = serialize($value);
        $compressed = gzcompress($serialized);

        return $compressed;
    }

    /**
     * Convert database value to plain text
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $uncompressed = gzuncompress($value);
        $unserialized = unserialize($uncompressed);

        return $unserialized;
    }

    /**
     * Get the type name
     *
     * @return string
     */
    public function getName()
    {
        return self::TYPE;
    }

    /**
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getBlobTypeDeclarationSQL($fieldDeclaration);
    }
}
