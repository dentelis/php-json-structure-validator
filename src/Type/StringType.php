<?php

namespace Dentelis\Validator\Type;

use Dentelis\Validator\TypeInterface;

class StringType implements TypeInterface
{

    protected bool $nullAllowed = false;
    protected bool $emptyAllowed = false;

    /**
     * @var string[]
     */
    protected array $regexpConditions = [];

    protected array $customConditions = [];

    /**
     * @param bool $value Допустим ли null в качестве значения
     * @todo переделать на вызов addCustom
     * @return $this
     */
    public function setNullAllowed(bool $value): self
    {
        $this->nullAllowed = $value;
        return $this;
    }

    /**
     * @param bool $value Допустима ли пустая строка в качестве значения
     * @todo переделать на вызов addCustom
     * @return $this
     */
    public function setEmptyAllowed(bool $value): self
    {
        $this->emptyAllowed = $value;
        return $this;
    }

    /**
     * @param string $regexp регулярное выражение которому должна удовлетворять строка
     * @todo переделать на вызов addCustom
     * @return $this
     */
    public function assertRegexp(string $regexp): self
    {
        $this->regexpConditions[] = $regexp;
        return $this;
    }

    public function assertLength(?int $min = null, ?int $max = null): self
    {
        //@todo implement через addCustom
        return $this;
    }

    //@todo подумать - может быть вынести в абстракт выше
    public function addCustom(\Closure $closure, ?string $errorMessage = null): self
    {
        $this->customConditions[] = [$closure, $errorMessage];
        return $this;
    }

    public function validate(mixed $value, array $path = [])
    {
        // TODO: Implement validate() method.
    }
}