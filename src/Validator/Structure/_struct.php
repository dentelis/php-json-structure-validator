<?php

namespace EntelisTeam\Validator\Structure;

//@todo сделать интерфейсом, enum/_simpleType implements this
abstract class _struct
{
    abstract function validate(mixed $value, string $path = '');
}

