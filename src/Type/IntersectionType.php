<?php
declare(strict_types=1);

namespace Dentelis\StructureValidator\Type;

use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\TypeInterface;
use RuntimeException;
use Throwable;

/**
 * @todo Think about allowing closure as one of types
 */
class IntersectionType implements TypeInterface
{

    /**
     * @param TypeInterface[] $types
     */
    public function __construct(protected array $types)
    {
        $this
            ->ensureTypesAreTypes($this->types)
            ->ensureMinimumOfTwoTypes($this->types);
    }

    private function ensureMinimumOfTwoTypes(array $types): self
    {
        if (count($types) < 2) {
            throw new RuntimeException(
                'An intersection type must be composed of at least two types',
            );
        }
        return $this;
    }

    private function ensureTypesAreTypes(array $types): self
    {
        foreach ($types as $type) {
            if (!($type instanceof TypeInterface)) {
                throw new RuntimeException(
                    'An intersection type must be composed of classes implementing ' . TypeInterface::class,
                );
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function validate(mixed $value, string $path = ''): true
    {
        foreach ($this->types as $type) {
            try {
                $result = $type->validate($value, $path);
            } catch (Throwable) {
                continue;
            }
            //there were no exceptions
            return true;

        }
        //@todo better text
        throw new ValidationException('type', 'one of intersected types', 'other');
    }
}