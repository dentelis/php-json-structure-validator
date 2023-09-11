<?php

namespace EntelisTeam\Validator\Structure;

use Exception;

class _property_array extends _property
{
    public _struct $type;

    /**
     * @param _struct|_struct[] $arrayItemType Массив типов данных
     * @param bool $nullAllowed Является ли null валидным значением
     * @param bool $emptyAllowed Является ли пустое значение валидным
     * @param $required Обязательно ли наличие этого свойства
     * @param array|string|null $possibleValues Возможные значения. Принимает или массив или enum.
     */
    function __construct(_struct|array $arrayItemType, bool $nullAllowed, bool $emptyAllowed, public $required = true, array|string|null $possibleValues = null)
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

        $this->type = new _array($arrayItemType, $nullAllowed, $emptyAllowed, $possibleValues);
    }
}