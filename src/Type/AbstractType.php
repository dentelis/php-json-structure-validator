<?php
declare(strict_types=1);

namespace Dentelis\Validator\Type;

use Closure;
use Dentelis\Validator\Exception\ValidationException;
use Dentelis\Validator\TypeInterface;
use RuntimeException;

abstract class AbstractType implements TypeInterface
{
    private bool $nullAllowed = false;
    /**
     * @var Closure[]
     */
    private array $customConditions = [];

    public function __construct(?string $requiredType = null)
    {
        if (!is_null($requiredType)) {
            $this->addCustom(function ($value) use ($requiredType) {
                return ((is_null($value) && $this->getNullAllowed()) || gettype($value) === $requiredType) ?: throw new ValidationException('type', $requiredType, gettype($value));
            }, false);
        }
    }

    /**
     * Adds custom validation rule
     * @todo consider about making protected
     * @param Closure $closure function($value, $path) must return true in case of success
     * @param bool $skipIfNull do not run check if value == null
     * @return $this
     */
    public final function addCustom(Closure $closure, bool $skipIfNull = true): self
    {
        $this->customConditions[] = [$closure, $skipIfNull];
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
     * Validation
     * @param mixed $value
     * @param array $path
     * @return void
     * @throws ValidationException
     */
    public function validate(mixed $value, array $path = []): true
    {
        foreach ($this->customConditions as list($closure, $skipIfNull)) {
            if (is_null($value) && $skipIfNull) {
                continue;
            }
            try {
                $result = $closure($value, $path);
                if ($result !== true) {
                    throw new ValidationException('Custom assert', 'true', $result);
                }
            } catch (ValidationException $exception) {
                $exception->setPath($path);
                throw $exception;
            }
        }
        return true;
    }

    /**
     * Assert value === expected
     * @param mixed ...$expected
     * @return $this
     */
    public function assertValue(mixed ...$expected): self
    {
        return $this->assertValueIn($expected, true);
    }

    /**
     * Assert value in [expected]
     * @param array $expectedValues
     * @param bool $strict
     * @return $this
     */
    public function assertValueIn(array $expectedValues, bool $strict = false): self
    {
        foreach ($expectedValues as $value) {
            if (is_null($value)) {
                throw new RuntimeException('Null is not possible value for assertValueIn. Use setNullAllowed() instead.');
            }
        }
        return $this->addCustom(function ($value) use ($expectedValues, $strict) {
            return in_array($value, $expectedValues, $strict) ?: throw new ValidationException('value', join(',', $expectedValues), $value);
        });
    }

}