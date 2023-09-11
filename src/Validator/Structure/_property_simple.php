<?php

namespace EntelisTeam\Validator\Structure;

use EntelisTeam\Validator\Enum\_simpleType;
use Exception;

class _property_simple extends _property
{
    public _struct $type;

    /**
     * @param _simpleType $simpleType Тип значения свойства
     * @param bool $nullAllowed Является ли null валидным значением
     * @param $required Обязательно ли наличие этого свойства
     * @param array|string|null $possibleValues Возможные значения. Принимает или массив или enum.
     */
    function __construct(_simpleType $simpleType, bool $nullAllowed, public $required = true, array|string|null $possibleValues = null)
    {
        if (is_string($possibleValues)) {
            if (!enum_exists($possibleValues)) {
                throw new Exception('Possible values MUST BE array or enum');
            }
            //$possibleValues - enum class
            $tmp = array_column($possibleValues::cases(), 'value');
            if (empty($tmp)) {
                //unbacked enum case
                $tmp = array_column($possibleValues::cases(), 'name');
            }
            $possibleValues = $tmp;
        }

        $this->type = new _simple($simpleType, $nullAllowed, $possibleValues);
    }
}