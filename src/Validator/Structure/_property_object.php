<?php

namespace EntelisTeam\Validator\Structure;

class _property_object extends _property
{
    /**
     * @todo тут какая-то грязь вообще - зачем отдельный аргумент $nullAllowed если он может быть передан в объекте $objectType
     */
    function __construct(_object|_polymorph $objectType, bool $nullAllowed, $required = true)
    {
        if ($objectType instanceof _object) {
            $objectType->nullAllowed = $nullAllowed;
        }
        parent::__construct(
            $objectType,
            $required
        );
    }
}