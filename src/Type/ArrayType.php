<?php
declare(strict_types=1);

namespace Dentelis\Validator\Type;

use Closure;
use Dentelis\Validator\Exception\ValidationException;
use Dentelis\Validator\TypeInterface;
use RuntimeException;

class ArrayType extends AbstractType implements TypeInterface
{
    public function __construct()
    {
        parent::__construct('array');
    }

    /**
     * Assert array element type.
     * @param TypeInterface|Closure $type Behavior differs depending on the type.
     *    If TypeInterface passed, it runs TypeInterface::validate for each element.
     *    If Closure passed, for each element type is determined before validation like Closure($item):TypeInterface
     * @return $this
     */
    public function assertType(TypeInterface|Closure $type): self
    {
        $this->addCustom(function (array $values, array $path) use ($type) {
            $realType = $type;
            foreach ($values as $key => $value) {
                if (is_callable($type)) {
                    $realType = $type($value);
                    if (!($realType instanceof TypeInterface)) {
                        throw new RuntimeException('Property type must be instance of TypeInterface');
                    }
                }
                $realType->validate($value, [...$path, '[' . $key . ']']);
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
        $this->addCustom(function ($value) use ($expectedCounts) {
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
            $this->addCustom(function ($value) use ($min) {
                return count($value) >= $min ?: throw new ValidationException('array count', '>=' . $min, count($value));
            });
        }
        if (!is_null($max)) {
            $this->addCustom(function ($value) use ($max) {
                return count($value) <= $max ?: throw new ValidationException('array count', '<=' . $max, count($value));
            });
        }
        return $this;
    }

}