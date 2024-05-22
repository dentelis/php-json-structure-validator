<?php
declare(strict_types=1);

namespace Dentelis\StructureValidator\Type;

use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\TypeInterface;
use RuntimeException;

class ArrayType extends AbstractType implements TypeInterface
{
    public function __construct()
    {
        parent::__construct('array');
    }

    /**
     * Assert array element type.
     * @param TypeInterface|callable(mixed $arrayItem):TypeInterface $type Type to be validated.
     *    Behavior differs depending on the type.
     *    If TypeInterface passed, it runs TypeInterface::validate for each element.
     *    If callable passed, each element real type is determined before validation
     * @return $this
     */
    public function assertType(TypeInterface|callable $type): self
    {
        $this->addCustom(function (array $values, string $path) use ($type): bool {
            $realType = $type;
            foreach ($values as $key => $value) {
                if (is_callable($type)) {
                    try {
                        $realType = $type($value);
                    } catch (\UnhandledMatchError) {
                        throw new RuntimeException('Array item type must be instance of TypeInterface');
                    }
                    if (!($realType instanceof TypeInterface)) {
                        throw new RuntimeException('Array item type must be instance of TypeInterface');
                    }
                }
                $realType->validate($value, $path . '[' . $key . ']');
            }
            return true;
        });
        return $this;
    }

    /**
     * Assert array has 0 elements
     * @return $this
     */
    public function assertEmpty(): self
    {
        return $this->assertCount(0);
    }

    /**
     * Assert array has exactly N elements
     * @param int ...$expected
     * @return $this
     */
    public function assertCount(int ...$expected): self
    {
        return $this->assertCountIn($expected);
    }

    /**
     * Assert array count is in provided values
     * @param Int[] $expectedCounts possible count values
     */
    public function assertCountIn(array $expectedCounts): self
    {
        $this->addCustom(function (array $value) use ($expectedCounts): bool {
            return in_array(count($value), $expectedCounts, true) ?: throw new ValidationException('array count', join(',', $expectedCounts), count($value));
        });
        return $this;
    }

    /**
     * Assert array has 1 or more elements
     * @return $this
     */
    public function assertNotEmpty(): self
    {
        return $this->assertCountInterval(min: 1);
    }

    /**
     * Assert array count is in provided interval (inclusive)
     * @param int|null $min
     * @param int|null $max
     * @return $this
     */
    public function assertCountInterval(?int $min = null, ?int $max = null): self
    {
        if (!is_null($min)) {
            $this->addCustom(function (array $value) use ($min): bool {
                return count($value) >= $min ?: throw new ValidationException('array count', '>=' . $min, count($value));
            });
        }
        if (!is_null($max)) {
            $this->addCustom(function (array $value) use ($max): bool {
                return count($value) <= $max ?: throw new ValidationException('array count', '<=' . $max, count($value));
            });
        }
        return $this;
    }

}