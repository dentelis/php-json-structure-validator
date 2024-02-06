<?php
declare(strict_types=1);

namespace Dentelis\StructureValidator\Type;

use Closure;
use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\TypeInterface;
use RuntimeException;

class ObjectType extends AbstractType implements TypeInterface
{

    /**
     * @var array(TypeInterface|Closure $type, bool $mandatory)
     */
    protected array $properties = [];
    private bool $isExtensible = false;

    public function __construct()
    {
        parent::__construct('object');


        $this->addCustom(function (object $value): bool {
            //check we have all mandatory properties
            $missedProperties = [];
            $requiredProperties = [];
            foreach ($this->properties as $propertyName => [$typeOrClosure, $mandatory]) {
                if ($mandatory) {
                    $requiredProperties[] = $propertyName;
                    if (!self::propertyExistsInValue($propertyName, $value)) {
                        $missedProperties[] = $propertyName;
                    }
                }
            }
            if (count($missedProperties) > 0) {
                throw new ValidationException('properties', join(',', $requiredProperties), array_keys((array)$value));
            }

            //check we don't have undescribed properties
            if ($this->getExtensible() === false) {
                $expectedProperties = array_keys($this->properties);
                $actualProperties = array_keys((array)$value);
                return
                    array_diff($actualProperties, $expectedProperties,) === []
                        ?: throw new ValidationException('properties', join(', ', $expectedProperties), join(', ', $actualProperties));
            }

            return true;

        });


        //determine and validate properties type
        $this->addCustom(function (mixed $value, array $path): bool {
            foreach ($this->properties as $propertyName => [$typeOrClosure, $mandatory]) {
                $type = is_callable($typeOrClosure) ? $typeOrClosure($value) : $typeOrClosure;
                if (!($type instanceof TypeInterface)) {
                    throw new RuntimeException('Property type must be instance of TypeInterface');
                }
                $propertyExists = self::propertyExistsInValue($propertyName, $value);
                if ($propertyExists) {
                    $type->validate($value->$propertyName, [...$path, $propertyName]);
                } elseif ($mandatory === true) {
                    throw new ValidationException($propertyName, 'value', 'not found', [...$path, $propertyName]);
                }
            }
            return true;
        });

    }

    protected static final function propertyExistsInValue(string $property, object $object): bool
    {
        return array_key_exists($property, (array)$object);
    }

    protected function getExtensible(): bool
    {
        return $this->isExtensible;
    }

    /**
     * Add property to the object
     * @param string $property property name
     * @param TypeInterface|Closure $type Type. Behavior differs depending on the type.
     *    If TypeInterface passed, it runs TypeInterface::validate for property value
     *    If Closure passed, property real type is determined before validation like Closure($object):TypeInterface
     * @param bool $mandatory false if this property can be missed from the object
     * @return $this
     */
    public function addProperty(string $property, TypeInterface|Closure $type, bool $mandatory = true): self
    {
        $this->properties[$property] = [$type, $mandatory];
        return $this;
    }

    /**
     * Allows object to contain undescribed fields)
     * @return $this
     */
    public function setExtensible(): self
    {
        $this->isExtensible = true;
        return $this;
    }

}