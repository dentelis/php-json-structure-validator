<?php

namespace EntelisTeam\Validator\Structure;

use Closure;
use EntelisTeam\Validator\Enum\_simpleType;

class _polymorph extends _struct
{
    /**
     * @param Closure $getStruct ($value):_struct
     */
    function __construct(protected Closure $getStruct)
    {
    }

    function validate(mixed $value, string $path = '')
    {
        $closure = $this->getStruct;
        $struct = $closure($value);

        //@todo добавить ошибку если возвращенный тип не $struct

        if (is_null($struct)) {
            $struct = new _simple(_simpleType::NULL, true);
        }

        /**
         * @var $struct _struct
         */
        return $struct->validate($value, $path);
    }
}