<?php
declare(strict_types=1);

namespace Dentelis\Validator\Type;

use Dentelis\Validator\Exception\ValidationException;
use Dentelis\Validator\TypeInterface;

class FloatIntegerType extends FloatType implements TypeInterface
{

    public function __construct()
    {
        $this->addCustom(function ($value) {
            return ((is_null($value) && $this->getNullAllowed()) || (gettype($value) === 'double' || gettype($value) === 'integer')) ?: throw new ValidationException('type', 'double|integer', gettype($value));
        }, false);
    }

}