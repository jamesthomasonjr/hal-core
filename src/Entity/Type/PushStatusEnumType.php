<?php
# src/QL/Hal/Core/Entity/Type/PushStatusEnumType.php

namespace QL\Hal\Core\Entity\Type;

use Doctrine\DBAL\Types\Type as BaseType;

/**
 *  Push Status Doctrine Enum Type
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 */
class PushStatusEnumType extends BaseType
{
    use EnumTypeTrait;

    /**
     *  The enum data type
     */
    const TYPE = "pushstatusenum";

    /**
     *  The enum allowed values
     *
     *  @var array
     */
    protected $values = ['Waiting','Pushing','Error','Success'];
}
