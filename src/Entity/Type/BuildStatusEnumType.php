<?php
# src/QL/Hal/Core/Entity/Type/BuildStatusEnumType.php

namespace QL\Hal\Core\Entity\Type;

use Doctrine\DBAL\Types\Type as BaseType;

/**
 *  Build Status Doctrine Enum Type
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 */
class BuildStatusEnumType extends BaseType
{
    use EnumTypeTrait;

    /**
     *  The enum data type
     */
    const TYPE = 'buildstatusenum';

    /**
     *  The enum allowed values
     *
     *  @var array
     */
    protected $values = ['Waiting','Building','Success','Error','Removed'];
}
