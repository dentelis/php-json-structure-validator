<?php

namespace EntelisTeam\Validator\Structure;

class _property_object extends _property
{
    function __construct(_object $objectType, bool $nullAllowed, $required = true)
    {
        $objectType->nullAllowed = $nullAllowed;
        parent::__construct(
            $objectType,
            $required
        );
    }
}