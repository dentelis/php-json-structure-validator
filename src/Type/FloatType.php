<?php
declare(strict_types=1);

namespace Dentelis\StructureValidator\Type;

use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\TypeInterface;

class FloatType extends AbstractType implements TypeInterface
{

    public function __construct()
    {
        parent::__construct('double'); //php is strange, float has type of double
    }

    public function assertInterval(float|int|null $min = null, float|int|null $max = null): self
    {
        if (!is_null($min)) {
            $this->addCustom(function (float $value) use ($min): bool {
                return ($value) >= $min ?: throw new ValidationException('value', '>=' . $min, ($value));
            });
        }
        if (!is_null($max)) {
            $this->addCustom(function (float $value) use ($max): bool {
                return ($value) <= $max ?: throw new ValidationException('value', '<=' . $max, ($value));
            });
        }
        return $this;
    }

    public function assertPositive(): self
    {
        $this->addCustom(function (float $value): bool {
            return ($value) > 0 ?: throw new ValidationException('value', '>0', ($value));
        });
        return $this;
    }

    public function assertNegative(): self
    {
        $this->addCustom(function (float $value): bool {
            return ($value) < 0 ?: throw new ValidationException('value', '<0', ($value));
        });
        return $this;
    }

}