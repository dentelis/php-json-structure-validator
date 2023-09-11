<?php

namespace EntelisTeam\Validator\Exception;

use Exception;

class InvalidValueException extends Exception
{
    public function __construct(string $path, string $excepted, string $present)
    {
        parent::__construct("$path value incorrect - $excepted expected, $present found");
    }
}