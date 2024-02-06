<?php
declare(strict_types=1);

namespace Dentelis\StructureValidator\Type;

use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\TypeInterface;

class StringType extends AbstractType implements TypeInterface
{

    public function __construct()
    {
        parent::__construct('string');
    }

    public function assertNotEmpty(): self
    {
        return $this->assertLength(min: 1);
    }

    public function assertLength(?int $min = null, ?int $max = null): self
    {
        if (!is_null($min)) {
            $this->addCustom(function (mixed $value) use ($min): bool {
                return mb_strlen($value) >= $min ?: throw new ValidationException('string length', '>= ' . $min, mb_strlen($value));
            });
        }
        if (!is_null($max)) {
            $this->addCustom(function (mixed $value) use ($max): bool {
                return mb_strlen($value) <= $max ?: throw new ValidationException('string length', '<= ' . $max, mb_strlen($value));
            });
        }
        return $this;
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
        return $this->addCustom(function (mixed $value) use ($regexp, $regexpTitle): bool {
            return preg_match($regexp, $value) === 1 ?: (throw new ValidationException('string match regexp', $regexpTitle ?? $regexp, $value));
        });
    }

    /**
     * Требует чтобы строка содержала в себе валидный email
     */
    public function assertEmail(): self
    {
        return $this->addCustom(function (mixed $value): bool {
            return (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) ?: (throw new ValidationException('string content', 'email', $value));
        });
    }

}