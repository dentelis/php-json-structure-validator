<?php

namespace tests\_dto;

use Dentelis\StructureValidator\Type\IntegerType;
use Dentelis\StructureValidator\Type\ObjectType;
use Dentelis\StructureValidator\Type\StringType;
use stdClass;

class UserDTO extends stdClass
{
    public function __construct(public mixed $id, public mixed $email)
    {
    }

    public static function getDefinition(): ObjectType
    {
        return (new ObjectType())
            ->addProperty('id', (new IntegerType())->assertPositive())
            ->addProperty('email', (new StringType())->assertEmail());
    }

    public function setId(mixed $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setEmail(mixed $email): self
    {
        $this->email = $email;
        return $this;
    }
}