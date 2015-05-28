<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Type\EnumType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;

trait EnumTypeTrait
{
    /**
     * Convert Enum to database value
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @throws InvalidArgumentException
     *
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, static::values())) {
            throw new InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for enum. Must be one of %s.",
                    (string) $value,
                    $this->valuesAsString()
                )
            );
        }

        return $value;
    }

    /**
     *  Convert database value to string
     *
     *  @param mixed $value
     *  @param AbstractPlatform $platform
     *  @return null|string
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    /**
     * Get the Enum field definition
     *
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return sprintf("ENUM(%s) COMMENT '(DC2Type:%s)'", $this->valuesAsString(), $this->getName());
    }

    /**
     * Get the type name
     *
     * @return string
     */
    public function getName()
    {
        return static::TYPE;
    }

    /**
     * Get a string representation of valid enum values
     *
     * @return string
     */
    private function valuesAsString()
    {
        $values =  array_map(function ($val) {
            return "'$val'";
        }, static::values());

        return implode(', ', $values);
    }
}
