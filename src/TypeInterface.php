<?php

namespace Dentelis\StructureValidator;

use Dentelis\StructureValidator\Exception\ValidationException;
use RuntimeException;

interface TypeInterface
{
    /**
     * Asserts provided data matches current structure
     * @param mixed $value
     * @param string $path
     * @return true
     * @throws RuntimeException on invalid configuration (code issues)
     * @throws ValidationException on validation failure
     */
    public function validate(mixed $value, string $path = ''): true;
}