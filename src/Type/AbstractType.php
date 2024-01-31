<?php

namespace Dentelis\Validator\Type;

use Closure;
use Dentelis\Validator\Exception\ValidationException;
use Dentelis\Validator\TypeInterface;

/**
 * @todo возможно это AbstractSimpleType
 */
abstract class AbstractType implements TypeInterface
{
    /**
     * @var Closure[]
     */
    private array $customConditions = [];

    private bool $nullAllowed = false;

    public function __construct(?string $requiredType = null)
    {
        if (!is_null($requiredType)) {
            $this->addCustom(function ($value) use ($requiredType) {
                return ((is_null($value) && $this->getNullAllowed()) || gettype($value) === $requiredType) ?: throw new ValidationException('type', $requiredType, gettype($value));
            }, false);
        }
    }

    public function addCustom(Closure $closure, bool $skipIfNull = true): self
    {
        $this->customConditions[] = [$closure, $skipIfNull];
        return $this;
    }

    private function getNullAllowed(): bool
    {
        return $this->nullAllowed;
    }

    /**
     * @param mixed $value
     * @param array $path
     * @return void
     * @throws ValidationException
     */
    public function validate(mixed $value, array $path = [])
    {
        foreach ($this->customConditions as list($closure, $skipIfNull)) {
            if (is_null($value) && $skipIfNull) {
                continue;
            }
            try {
                $result = $closure($value);
                if ($result !== true) {
                    throw new ValidationException('Custom assert', 'true', $result);
                }
            } catch (ValidationException $exception) {
                $exception->setPath($path);
                throw $exception;
            }
        }
    }

    public function assertValueIn(array $values): self
    {
        if (in_array(null, $values)) {
            $this->setNullAllowed();
        }
        return $this->addCustom(function ($value) use ($values) {
            return in_array($value, $values) ?: throw new ValidationException('value', 'from array(...)', $value);
        });
    }

    /**
     * @param bool $value Допустим ли null в качестве значения
     * @return $this
     */
    public function setNullAllowed(): self
    {
        $this->nullAllowed = true;
        return $this;
    }

}