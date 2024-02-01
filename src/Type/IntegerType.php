<?php
declare(strict_types=1);

namespace Dentelis\Validator\Type;

use Dentelis\Validator\Exception\ValidationException;
use Dentelis\Validator\TypeInterface;

class IntegerType extends AbstractType implements TypeInterface
{

    public function __construct()
    {
        parent::__construct('integer');
    }

    public function assertInterval(?int $min = null, ?int $max = null): self
    {
        if (!is_null($min)) {
            $this->addCustom(function ($value) use ($min) {
                return ($value) >= $min ?: throw new ValidationException('value', '>=' . $min, ($value));
            });
        }
        if (!is_null($max)) {
            $this->addCustom(function ($value) use ($max) {
                return ($value) <= $max ?: throw new ValidationException('value', '<=' . $max, ($value));
            });
        }
        return $this;
    }

    public function assertPositive(): self
    {
        $this->addCustom(function ($value) {
            return ($value) > 0 ?: throw new ValidationException('value', '>0', ($value));
        });
        return $this;
    }

    public function assertNegative(): self
    {
        $this->addCustom(function ($value) {
            return ($value) < 0 ?: throw new ValidationException('value', '<0', ($value));
        });
        return $this;
    }

}