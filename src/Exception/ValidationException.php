<?php

namespace Dentelis\StructureValidator\Exception;

use Exception;
use Stringable;

class ValidationException extends Exception
{
    public function __construct(
        string       $checked,
        mixed        $expected,
        mixed        $actual,
        public ?string $path = null)
    {
        parent::__construct(sprintf(
            'Validation of %s failed: "%s" expected, but "%s" found.',
            $checked,
            (is_scalar($expected) || $expected instanceof Stringable ? $expected : '...'),
            (is_scalar($actual) || $actual instanceof Stringable ? $actual : '...'),
        ));
    }

    public function setPath(string $path): static
    {
        $this->path = $path;
        return $this;
    }
}