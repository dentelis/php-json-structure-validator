<?php

namespace EntelisTeam\Validator\Structure;

use EntelisTeam\Validator\Enum\_simpleType;
use EntelisTeam\Validator\Exception\InvalidValueException;
use EntelisTeam\Validator\Exception\NullNotAllowedException;

class _simple extends _struct
{
    function __construct(private _simpleType $type, private bool $nullAllowed, private ?array $possibleValues = null)
    {
    }

    function validate(mixed $value, string $path = '')
    {
        if (!$this->nullAllowed && is_null($value)) {
            throw new NullNotAllowedException($path);
        } elseif ($this->nullAllowed && is_null($value)) {
            return;
        } else {
            $this->type->validate($value, $path);
        }

        //проверяем возможные значения
        if (!is_null($this->possibleValues)) {
            $possibleValuesInverted = array_flip(array_values($this->possibleValues));
            if (!array_key_exists($value, $possibleValuesInverted)) {
                throw new InvalidValueException($path, join('|', $this->possibleValues), $value);
            }
        }

    }
}