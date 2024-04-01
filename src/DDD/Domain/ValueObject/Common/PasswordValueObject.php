<?php

declare(strict_types=1);

namespace DDD\Domain\ValueObject\Common;

class PasswordValueObject
{
    /** @var string */
    protected $value;

    public function __construct(string $value, bool $encriptor = false)
    {
        $this->value = $encriptor ? self::encript($value) : $value;
    }

    public static function from(string $value): self
    {
        return new static($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value();
    }

    private static function encript(string $value)
    {
        return bcrypt($value);
    }
}
