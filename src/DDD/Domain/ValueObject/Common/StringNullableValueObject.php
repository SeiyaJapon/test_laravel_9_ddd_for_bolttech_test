<?php

declare(strict_types=1);

namespace DDD\Domain\ValueObject\Common;

class StringNullableValueObject
{
    protected $value;

    public function __construct(string $value = null)
    {
        $this->value = $value;
    }

    public static function from(string $value = null): self
    {
        return new static($value);
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value();
    }
}
