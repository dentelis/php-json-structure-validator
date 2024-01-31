<?php

namespace Dentelis\Validator\Type;

use Closure;
use Dentelis\Validator\Exception\ValidationException;
use Dentelis\Validator\TypeInterface;
use Override;
use RuntimeException;

class ObjectType extends AbstractType implements TypeInterface
{

    /**
     * @var TypeInterface[]|Closure[]
     */
    protected array $properties = [];
    private bool $isExtensible = false;

    public function __construct()
    {
        parent::__construct('object');

        $this->addCustom(function ($value) {
            if ($this->getExtensible() === false) {
                $expectedProperties = array_keys($this->properties);
                $actualProperties = array_keys((array)$value);
                return
                    array_diff($expectedProperties, $actualProperties) === []
                    &&
                    array_diff($actualProperties, $expectedProperties) === []
                        ?: throw new ValidationException('properties', join(', ', $expectedProperties), join(', ', $actualProperties));
            } else {
                return true;
            }
        });

    }

    protected function getExtensible(): bool
    {
        return $this->isExtensible;
    }

    /**
     * @param string $property
     * @param TypeInterface|Closure $type
     * @param bool $mandatory
     * @return $this
     */
    public function addProperty(string $property, TypeInterface|Closure $type, bool $mandatory = true): self
    {
        $this->properties[$property] = [$type, $mandatory];
        return $this;
    }

    public function setExtensible(): self
    {
        $this->isExtensible = true;
        return $this;
    }

    #[Override]
    /**
     * @todo подумать - может это тоже можно в конструктор пихнуть через addCustom?
     */
    public function validate(mixed $value, array $path = []): bool
    {
        //вызываем базовую валидацию
        parent::validate($value, $path);

        //проверяем все определенные свойства
        if (!is_null($value)) {
            foreach ($this->properties as $propertyName => [$typeOrClosure, $mandatory]) {
                $type = is_callable($typeOrClosure) ? $typeOrClosure($value) : $typeOrClosure;
                if (!($type instanceof TypeInterface)) {
                    throw new RuntimeException('Property type must be instance of TypeInterface');
                }
                $propertyExists = array_key_exists($propertyName, (array)$value);
                if ($propertyExists) {
                    $type->validate($value->$propertyName, [...$path, $propertyName]);
                } elseif ($mandatory === true) {
                    throw new ValidationException($propertyName, 'value', 'not found', [...$path, $propertyName]);
                }
            }
        }
        return true;
    }

}