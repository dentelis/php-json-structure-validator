<?php

namespace EntelisTeam\Validator\Enum;

use EntelisTeam\Validator\Exception\EmptyValueException;
use EntelisTeam\Validator\Exception\InvalidTypeException;

enum _simpleType
{
    case INT;
    case STRING;
    case STRING_NOT_EMPTY;
    case BOOL;
    case FLOAT;

    public function validate(mixed $value, string $path)
    {
        switch ($this) {
            case self::FLOAT:
                if (gettype($value) == 'integer' || gettype($value) == 'double') {
                    break;
                } else {
                    throw new InvalidTypeException($path, self::getType(), gettype($value));
                }
            case self::STRING_NOT_EMPTY:
                if (gettype($value) === 'string' && !empty($value)) {
                    break;
                } elseif (gettype($value) === 'string' && empty($value)) {
                    throw new EmptyValueException($path);
                } else {
                    throw new InvalidTypeException($path, self::getType(), gettype($value));
                }
            default:
                if ($this->getType() === gettype($value)) {
                    break;
                } else {
                    throw new InvalidTypeException($path, self::getType(), gettype($value));
                }
        }
    }

    public function getType(): string
    {
        return match ($this) {
            self::INT => 'integer',
            self::STRING => 'string',
            self::STRING_NOT_EMPTY => 'string',
            self::BOOL => 'boolean',
            self::FLOAT => 'double',
        };
    }
}