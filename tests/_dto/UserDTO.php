<?php

namespace tests\_dto;

use Dentelis\Validator\Type\IntegerType;
use Dentelis\Validator\Type\ObjectType;
use Dentelis\Validator\Type\StringType;
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