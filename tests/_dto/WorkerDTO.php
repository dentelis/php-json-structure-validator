<?php

namespace tests\_dto;

use Dentelis\Validator\Type\IntersectionType;
use Dentelis\Validator\Type\NullType;
use Dentelis\Validator\Type\ObjectType;
use Dentelis\Validator\Type\StringType;
use stdClass;

class WorkerDTO extends stdClass
{
    function __construct(public mixed $name, public mixed $boss = null)
    {
    }

    public static function getDefinition(): ObjectType
    {
        $worker = (new ObjectType())
            ->addProperty('name', (new StringType())->assertNotEmpty());
        $worker->addProperty('boss', (new IntersectionType([
            $worker,
            new NullType(),
        ])));
        return $worker;
    }

    public function setBoss(mixed $boss): self
    {
        $this->boss = $boss;
        return $this;
    }
}