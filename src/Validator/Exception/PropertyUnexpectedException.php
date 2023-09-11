<?php

namespace EntelisTeam\Validator\Exception;

use Exception;

class PropertyUnexpectedException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct("$path property unexpected - there is no such property in expected structure!");
    }
}