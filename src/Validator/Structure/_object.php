<?php

namespace EntelisTeam\Validator\Structure;

use Exception;
use EntelisTeam\Validator\Exception\ComplexException;
use EntelisTeam\Validator\Exception\InvalidTypeException;
use EntelisTeam\Validator\Exception\MissedActualPropertyException;
use EntelisTeam\Validator\Exception\MissedPropertyException;
use EntelisTeam\Validator\Exception\NullNotAllowedException;

class _object extends _struct
{
    /**
     * @param _property[] $fields
     * @param bool $nullAllowed
     */
    function __construct(private array $fields, public bool $nullAllowed = false)
    {
    }

    public function updateField(string $key, _property $value)
    {
        $this->fields[$key] = $value;
    }

    function validate(mixed $value, string $path)
    {
        if (is_null($value)) {
            if (!$this->nullAllowed) {
                throw new NullNotAllowedException($path);
            }
        } else {
            if (!is_object($value)) {
                throw new InvalidTypeException($path, 'object', gettype($value));
            }

            $errors = [];

            $actualStruct = [];
            foreach ($this->fields as $key => $field) {
                $actualStruct[$key] = $field;
            }

            foreach ((array)$value as $key => $field) {
                try {
                    if (!array_key_exists($key, $actualStruct)) {
                        throw new MissedActualPropertyException($path . '.' . $key);
                    }
                } catch (ComplexException $e) {
                    //значит изнутри приехало много сразу ошибок
                    $innerErrors = unserialize($e->getMessage());
                    foreach ($innerErrors as $item) {
                        $errors[$item] = 1;
                    }
                } catch (Exception $e) {
                    $errors[$e->getMessage()] = 1;
                }
            }

            foreach ($this->fields as $key => $field) {
                try {
                    //там были заморочки с isset и null значением, поэтому вот так извращенно
                    if (!array_key_exists($key, (array)$value) && $field->required === true) {
                        throw new MissedPropertyException($path . '.' . $key);
                    } elseif (!array_key_exists($key, (array)$value) && $field->required === false) {
                        return;
                    } else {
                        $field->type->validate($value->$key, $path . '.' . $key);
                    }
                } catch (ComplexException $e) {
                    //значит изнутри приехало много сразу ошибок
                    $innerErrors = unserialize($e->getMessage());
                    foreach ($innerErrors as $item) {
                        $errors[$item] = 1;
                    }
                } catch (Exception $e) {
                    $errors[$e->getMessage()] = 1;
                }
            }

            if (count($errors)) {
                throw new ComplexException(serialize(array_keys($errors)));
            }
        }
    }
}