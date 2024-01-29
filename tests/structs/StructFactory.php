<?php

namespace tests\structs;

use EntelisTeam\Validator\Enum\_simpleType;
use EntelisTeam\Validator\Structure\_object;
use EntelisTeam\Validator\Structure\_property_array;
use EntelisTeam\Validator\Structure\_property_object;
use EntelisTeam\Validator\Structure\_property_simple;

class StructFactory
{
    static function simpleClass(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false, regexp: '~^(.+\s.+)$~'),
        ]);
    }

    static function classWithArrayOfClass(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'colors' => new _property_array(static::colorClass(), false, false),
        ]);
    }

    static function colorClass(): _object
    {
        return new _object([
            'r' => new _property_simple(_simpleType::INT, false),
            'g' => new _property_simple(_simpleType::INT, false),
            'b' => new _property_simple(_simpleType::INT, false),
        ]);
    }

    static function carClass(): _object
    {
        return new _object([
            'model' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'color' => new _property_object(static::colorClass(), false),
        ]);
    }

}