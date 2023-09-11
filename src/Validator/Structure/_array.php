<?php

namespace EntelisTeam\Validator\Structure;

use EntelisTeam\Validator\Exception\ComplexException;
use EntelisTeam\Validator\Exception\EmptyValueException;
use EntelisTeam\Validator\Exception\InvalidTypeException;
use EntelisTeam\Validator\Exception\InvalidValueException;
use EntelisTeam\Validator\Exception\NullNotAllowedException;
use Exception;

class _array extends _struct
{
    /**
     * @param _struct|_struct[] $type массив может быть либо плоский, либо содержать несколько разных типов данных @todo
     * @param bool $nullAllowed
     * @param bool $emptyAllowed
     */
    function __construct(private _struct|array $type, private bool $nullAllowed, private bool $emptyAllowed = true, private ?array $possibleValues = null)
    {
    }

    /**
     * @param array $value
     * @param string $path
     */
    function validate(mixed $value, string $path = '')
    {
        if (is_null($value)) {
            if (!$this->nullAllowed) {
                throw new NullNotAllowedException($path);
            }
        } else {
            if (!is_array($value)) {
                throw new InvalidTypeException($path, 'array', gettype($value));
            }
            if (!$this->emptyAllowed && count($value) === 0) {
                throw new EmptyValueException($path);
            }
            //проверяем структуру элементов
            $errors = [];
            foreach ($value as $key => $item) {
                //@todo вынести в отдельную функцию
                try {
                    if (!is_null($this->possibleValues)) {
                        $possibleValuesInverted = array_flip(array_values($this->possibleValues));
                        if (!array_key_exists($item, $possibleValuesInverted)) {
                            throw new InvalidValueException($path, join('|', $this->possibleValues), $item);
                        }
                    }

                    if ($this->type instanceof _struct) {

                        $this->type->validate($item, $path . '[]');

                    } else {
                        //несколько вариантов, проверяем каждый
                        $validateFailCnt = 0;
                        foreach ($this->type as $type) {
                            try {
                                $type->validate($item, $path . '[]');
                            } catch (\Throwable $e) {
                                $validateFailCnt++;
                            }
                        }
                        if ($validateFailCnt >= count($this->type))
                        {
                            //@todo переделать
                            throw new InvalidTypeException($path, 'several', 'no one');
                        }

                    }

                } catch (ComplexException $e) {
                    //значит изнутри приехало много сразу ошибок
                    $innerErrors = unserialize($e->getMessage());
                    foreach ($innerErrors as $elem) {
                        $errors[$elem] = 1;
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