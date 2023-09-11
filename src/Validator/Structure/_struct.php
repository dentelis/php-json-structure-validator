<?php

namespace EntelisTeam\Validator\Structure;

abstract class _struct
{
    abstract function validate(mixed $value, string $path);
}

