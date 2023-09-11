<?php

namespace EntelisTeam\Validator\Structure;

use Closure;

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

        /**
         * @var $struct _struct
         */
        return $struct->validate($value, $path);
    }
}