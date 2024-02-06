<?php

namespace tests\_dto;

use Dentelis\StructureValidator\Type\IntersectionType;
use Dentelis\StructureValidator\Type\NullType;
use Dentelis\StructureValidator\Type\ObjectType;
use Dentelis\StructureValidator\Type\StringType;
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