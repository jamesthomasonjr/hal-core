<?php
/**
 * CompressedJSONArrayType Doctrine Type
 *
 * Consider adding the following to the "require" section of your composer.json
 *
 *     "ext-zlib": "*",
 *
 * @license MIT
 */

namespace Hal\Core\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType;
use Doctrine\DBAL\Types\ConversionException;

/**
 * A Doctrine Type that stores JSON Arrays with string compression into BLOBs
 *
 * @see https://gist.github.com/baileyp/8275c5774615b45fcbed
 * @author Peter Bailey <b33tle@gmail.com>
 */
class CompressedJSONArrayType extends JsonArrayType
{
    const NAME = 'compressed_json_array';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getBlobTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null !== $value) {

            $converted = gzuncompress($value);
            if (false === $converted) {
                throw ConversionException::conversionFailed($value, $this->getName());
            }

        } else {
            $converted = null;
        }

        return parent::convertToPHPValue($converted, $platform);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
          return null;
        }

        $converted = gzcompress(parent::convertToDatabaseValue($value, $platform));
        if (false === $converted) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $converted;
    }
}
