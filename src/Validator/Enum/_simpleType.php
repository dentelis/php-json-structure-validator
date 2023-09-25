<?php

namespace EntelisTeam\Validator\Enum;

use EntelisTeam\Validator\Exception\EmptyValueException;
use EntelisTeam\Validator\Exception\InvalidTypeException;
use EntelisTeam\Validator\Exception\InvalidValueException;

enum _simpleType
{
    case INT;
    case STRING;
    case STRING_NOT_EMPTY;
    case STRING_URl;
    case BOOL;
    case FLOAT;

    public function validate(mixed $value, string $path = '', ?string $regexp = null)
    {
        switch ($this) {
            case self::FLOAT:
                if (gettype($value) === self::INT->getType() || gettype($value) === self::FLOAT->getType()) {
                    break;
                } else {
                    throw new InvalidTypeException($path, self::getType(), gettype($value));
                }
                break;
            case self::STRING:
            case self::STRING_URl:
            case self::STRING_NOT_EMPTY:
                if (gettype($value) !== $this->getType()) {
                    throw new InvalidTypeException($path, self::getType(), gettype($value));
                } elseif (($this === self::STRING_NOT_EMPTY || $this === self::STRING_URl) && empty($value)) {
                    throw new EmptyValueException($path);
                } elseif (!is_null($regexp) && preg_match($regexp, $value) !== 1) {
                    throw new InvalidValueException($path, $regexp, $value);
                } elseif ($this === self::STRING_URl && preg_match('~^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})$~', $value) !== 1) {
                    throw new InvalidValueException($path, 'URL', $value);
                }
                break;
            case self::INT:
            case self::BOOL:
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
            self::STRING_URl => 'string',
            self::BOOL => 'boolean',
            self::FLOAT => 'double',
        };
    }
}