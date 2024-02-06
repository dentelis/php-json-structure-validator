<?php
declare(strict_types=1);

namespace Dentelis\StructureValidator\Type;

use Dentelis\StructureValidator\TypeInterface;

class BooleanType extends AbstractType implements TypeInterface
{
    public function __construct()
    {
        parent::__construct('boolean');
    }
}