<?php

namespace EntelisTeam\Validator\Exception;

use Exception;

class NullNotAllowedException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct("$path value incorrect - null not allowed");
    }
}