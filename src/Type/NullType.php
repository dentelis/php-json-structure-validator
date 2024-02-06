<?php
declare(strict_types=1);

namespace Dentelis\StructureValidator\Type;

use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\TypeInterface;

class NullType extends AbstractType implements TypeInterface
{

    public function __construct()
    {
        $this->addCustom(function (mixed $value): bool {
            return (is_null($value)) ?: throw new ValidationException('type', 'NULL', gettype($value));
        }, false);
    }

}