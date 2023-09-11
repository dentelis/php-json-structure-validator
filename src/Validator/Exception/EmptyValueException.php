<?php

namespace EntelisTeam\Validator\Exception;

use Exception;

class EmptyValueException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct("$path value incorrect - empty not allowed");
    }
}