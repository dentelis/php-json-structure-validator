<?php

namespace Dentelis\Validator\Exception;

class ValidationException extends \Exception
{
    public function __construct(string $message, public array $path)
    {
        parent::__construct($message, $code);
    }
}