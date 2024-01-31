<?php

namespace Dentelis\Validator;

interface TypeInterface
{
    public function validate(mixed $value, array $path = []);
}