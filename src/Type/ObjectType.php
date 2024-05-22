<?php
declare(strict_types=1);

namespace Dentelis\StructureValidator\Type;

use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\TypeInterface;
use RuntimeException;

class ObjectType extends AbstractType implements TypeInterface
{

    /**
     * @var array<string, array{0: TypeInterface|callable(object $object):TypeInterface, 1: bool}>
     */
    protected array $properties = [];

    private bool $isExtensible = false;

    public function __construct()
    {
        parent::__construct('object');

        //@todo возможно есть смысл добавлять их не в конструкторе, а перед validate?
        $this->addCustom(function (object $value, string $path): bool {
            //check we have all mandatory properties
            $missedProperties = [];
            $requiredProperties = [];
            foreach ($this->properties as $propertyName => [ /*$typeOrCallable*/, $mandatory]) {
                if ($mandatory) {
                    $requiredProperties[] = $propertyName;
                    if (!self::propertyExistsInValue($propertyName, $value)) {
                        $missedProperties[] = $propertyName;
                    }
                }
            }

            if ($missedProperties !== []) {
                throw new ValidationException(
                    'properties',
                    join(',', $requiredProperties),
                    array_keys((array)$value),
                    $path . '.' . join('|', $missedProperties) . ''
                );
            }

            //check we don't have undescribed properties
            if ($this->getExtensible() === false) {
                $expectedProperties = array_keys($this->properties);
                $actualProperties = array_keys((array)$value);
                return
                    array_diff($actualProperties, $expectedProperties,) === []
                        ?: throw new ValidationException(
                        'properties',
                        join(', ', $expectedProperties),
                        join(', ', $actualProperties),
                        $path . '.' . join('|',  array_diff($actualProperties, $expectedProperties,)) . ''
                    );
            }

            return true;

        });


        //determine and validate properties type
        $this->addCustom(function (mixed $value, string $path): bool {
            foreach ($this->properties as $propertyName => [$typeOrCallable, $mandatory]) {
                try {
                    $type = is_callable($typeOrCallable) ? $typeOrCallable($value) : $typeOrCallable;
                } catch (\UnhandledMatchError) {
                    throw new RuntimeException('Property type must be instance of TypeInterface');
                }

                if (!($type instanceof TypeInterface)) {
                    throw new RuntimeException('Property type must be instance of TypeInterface');
                }

                $propertyExists = self::propertyExistsInValue($propertyName, $value);
                if ($propertyExists) {
                    $type->validate($value->$propertyName, $path . '.' . $propertyName);
                } elseif ($mandatory === true) {
                    throw new ValidationException($propertyName, 'value', 'not found', $path . '.' . $propertyName);
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
     * @param TypeInterface|callable(object $object):TypeInterface $type Type. Behavior differs depending on the type.
     *    If TypeInterface passed, it runs TypeInterface::validate for property value
     *    If callable passed, property real type is determined before validation like callable($object):TypeInterface
     * @param bool $mandatory false if this property can be missed from the object
     * @return $this
     */
    public function addProperty(string $property, TypeInterface|callable $type, bool $mandatory = true): static
    {
        $this->properties[$property] = [$type, $mandatory];
        return $this;
    }

    /**
     * Allows object to contain undescribed fields)
     * @return $this
     */
    public function setExtensible(): static
    {
        $this->isExtensible = true;
        return $this;
    }

}