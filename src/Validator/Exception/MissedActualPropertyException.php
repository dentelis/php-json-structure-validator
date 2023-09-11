<?php

namespace EntelisTeam\Validator\Exception;

use Exception;

class MissedActualPropertyException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct("$path actual property missed!");
    }
}