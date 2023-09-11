<?php

namespace EntelisTeam\Validator\Exception;

use Exception;

class MissedPropertyException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct("$path property missed!");
    }
}