<?php

namespace Dentelis\Validator\Type;

use Closure;
use Dentelis\Validator\Exception\ValidationException;
use Dentelis\Validator\TypeInterface;

class StringType extends AbstractType implements TypeInterface
{

    protected bool $nullAllowed = false;

    public function __construct()
    {
        $this->addCustom(function ($value) {
            return ((is_null($value) && $this->nullAllowed) || gettype($value) === 'string') ?: throw new ValidationException('type', 'string', gettype($value));
        }, false);
    }

    /**
     * @param bool $value Допустим ли null в качестве значения
     * @return $this
     * @todo вынести вверх
     */
    public function setNullAllowed(): self
    {
        $this->nullAllowed = true;
        return $this;
    }

    public function assertLength(?int $min = null, ?int $max = null): self
    {
        if (!is_null($min)) {
            $this->addCustom(function ($value) use ($min) {
                return mb_strlen($value) >= $min ?: throw new ValidationException('string length', '>= ' . $min, mb_strlen($value));
            });
        }
        if (!is_null($max)) {
            $this->addCustom(function ($value) use ($max) {
                return mb_strlen($value) <= $max ?: throw new ValidationException('string length', '<= ' . $max, mb_strlen($value));
            });
        }
        return $this;
    }

    /**
     * @todo возможно вынести на уровень выше
     */
    public function assertValueIn(array $values): self
    {
        return $this->addCustom(function ($value) use ($values) {
            return in_array($value, $values) ?: throw new ValidationException('string value', 'array(...)', $value);
        });
    }

    /**
     * Требует чтобы строка содержала в себе валидный url
     * @todo возможно переделать на filter_var('http://example.com', FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)
     */
    public function assertUrl(): self
    {
        return $this->assertRegexp('~^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})$~', 'URL');
    }

    /**
     * @param string $regexp регулярное выражение которому должна удовлетворять строка
     */
    public function assertRegexp(string $regexp, ?string $regexpTitle = null): self
    {
        return $this->addCustom(function ($value) use ($regexp, $regexpTitle) {
            return preg_match($regexp, $value) === 1 ?: (throw new ValidationException('string match regexp', $regexpTitle ?? $regexp, $value));
        });
    }

    /**
     * Требует чтобы строка содержала в себе валидный email
     */
    public function assertEmail(): self
    {
        return $this->addCustom(function ($value) {
            return (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) ?: (throw new ValidationException('string content', 'email', $value));
        });
    }


}