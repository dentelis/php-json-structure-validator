<?php

namespace EntelisTeam\Validator\Exception;

use Exception;

class InvalidTypeException extends Exception
{
    public function __construct(string $path, string $excepted, string $present)
    {
        parent::__construct("$path type value incorrect - $excepted expected, $present found");
    }
}