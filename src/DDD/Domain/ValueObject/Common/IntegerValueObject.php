<?php

declare(strict_types=1);

namespace DDD\Domain\ValueObject\Common;

class IntegerValueObject
{
    protected $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function from(int $value): self
    {
        return new static($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString()
    {
        return (string) $this->value();
    }
}
