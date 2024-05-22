<?php

namespace Dentelis\StructureValidator;

use Dentelis\StructureValidator\Exception\ValidationException;
use RuntimeException;

interface TypeInterface
{
    /**
     * Asserts provided data matches current structure
     * @throws RuntimeException on invalid configuration (code issues)
     * @throws ValidationException on validation failure
     */
    public function validate(mixed $value, string $path = ''): true;
}