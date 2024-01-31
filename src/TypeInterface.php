<?php

namespace Dentelis\Validator;

interface TypeInterface
{
    //@todo сделать $path массивом
    public function validate(mixed $value, array $path = []);
}