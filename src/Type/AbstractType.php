<?php
declare(strict_types=1);

namespace Dentelis\StructureValidator\Type;

use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\TypeInterface;
use RuntimeException;

abstract class AbstractType implements TypeInterface
{
    private bool $nullAllowed = false;

    private array $customConditions = [];

    protected ?string $requiredType = null;

    public function __construct(?string $requiredType = null)
    {
        $this->requiredType = $requiredType;
        if (!is_null($this->requiredType)) {
            $this->addCustom(function (mixed $value): bool {
                return ((is_null($value) && $this->getNullAllowed()) || gettype($value) === $this->requiredType) ?: throw new ValidationException('type', $this->requiredType, gettype($value));
            }, false);
        }
    }

    /**
     * Adds custom validation rule
     * @param callable(mixed $value, string $path):bool $callable Custom check. Returns true on success.
     * @param bool $skipIfNull do not run check if value == null
     * @return $this
     * @todo consider about making protected
     */
    public final function addCustom(callable $callable, bool $skipIfNull = true): self
    {
        $this->customConditions[] = [$callable, $skipIfNull];
        return $this;
    }

    protected final function getNullAllowed(): bool
    {
        return $this->nullAllowed;
    }

    /**
     * Allows null as possible value
     * @return $this
     */
    public final function setNullAllowed(): self
    {
        $this->nullAllowed = true;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function validate(mixed $value, ?string $path = null): true
    {

        $path ??= $this->requiredType;

        foreach ($this->customConditions as [$closure, $skipIfNull]) {
            if (is_null($value) && $skipIfNull) {
                continue;
            }

            try {
                $result = $closure($value, $path);
            } catch (ValidationException $e) {
                if (is_null($e->path)) {
                    $e->setPath($path);
                }

                throw $e;
            }

            if ($result !== true) {
                throw new ValidationException('Custom assert', 'true', $result, $path);
            }
        }

        return true;
    }

    /**
     * Assert value === expected
     * @return $this
     */
    public function assertValue(mixed ...$expected): self
    {
        return $this->assertValueIn($expected, true);
    }

    /**
     * Assert value in [expected]
     * @return $this
     */
    public function assertValueIn(array $expectedValues, bool $strict = false): self
    {
        foreach ($expectedValues as $value) {
            if (is_null($value)) {
                throw new RuntimeException('Null is not possible value for assertValueIn. Use setNullAllowed() instead.');
            }
        }

        return $this->addCustom(static function (mixed $value) use ($expectedValues, $strict) : bool {
            return in_array($value, $expectedValues, $strict) ?: throw new ValidationException('value', join(',', $expectedValues), $value);
        });
    }

}