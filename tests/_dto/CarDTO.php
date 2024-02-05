<?php

namespace tests\_dto;

use Dentelis\Validator\Type\ObjectType;
use Dentelis\Validator\Type\StringType;
use stdClass;

class CarDTO extends stdClass
{
    function __construct(public mixed $brand, public mixed $model)
    {
    }

    public static function getDefinition(): ObjectType
    {
        return (new ObjectType())
            ->addProperty('brand', (new StringType())->assertNotEmpty())
            ->addProperty('model', (new StringType())->assertNotEmpty());
    }

    public function setBrand(mixed $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    public function setModel(mixed $model): self
    {
        $this->model = $model;
        return $this;
    }
}