<?php

namespace Dentelis\Validator\Type;

use Dentelis\Validator\Exception\ValidationException;
use Dentelis\Validator\TypeInterface;

class NullType extends AbstractType implements TypeInterface
{

    public function __construct()
    {
        $this->addCustom(function ($value) {
            return (is_null($value)) ?: throw new ValidationException('type', 'NULL', gettype($value));
        }, false);
    }

}