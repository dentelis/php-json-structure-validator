<?php

namespace Dentelis\StructureValidator;

interface TypeInterface
{
    public function validate(mixed $value, array $path = []): true;
}