<?php

namespace Dentelis\Validator\Exception;

use Exception;
use Stringable;

class ValidationException extends Exception
{
    public function __construct(
        string       $checked,
        mixed        $expected,
        mixed        $actual,
        public array $path = [])
    {
        parent::__construct(sprintf(
            'Validation of %s failed: "%s" expected, but "%s" found.',
            $checked,
            (is_scalar($expected) || $expected instanceof Stringable ? $expected : '...'),
            (is_scalar($actual) || $actual instanceof Stringable ? $actual : '...'),
        ));
    }

    public function setPath(array $path): self
    {
        $this->path = $path;
        return $this;
    }
}