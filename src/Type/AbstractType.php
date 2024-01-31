<?php

namespace Dentelis\Validator\Type;

use Dentelis\Validator\Exception\ValidationException;
use Dentelis\Validator\TypeInterface;
use Closure;

abstract class AbstractType implements TypeInterface
{
    /**
     * @var Closure[]
     */
    protected array $customConditions = [];

    public function addCustom(Closure $closure, bool $skipIfNull = true): self
    {
        $this->customConditions[] = [$closure, $skipIfNull];
        return $this;
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
            };
            try {
                $result = $closure($value);
                if ($result !== true) {
                    throw new ValidationException('Something', 'something', $value);
                }
            } catch (ValidationException $exception) {
                $exception->setPath($path);
                throw $exception;
            }
        }
    }

}