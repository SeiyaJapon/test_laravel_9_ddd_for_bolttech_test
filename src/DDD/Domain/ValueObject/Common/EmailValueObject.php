<?php

declare(strict_types=1);

namespace DDD\Domain\ValueObject\Common;

use DDD\Domain\Exceptions\EmailNotValid;

class EmailValueObject
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->validate($value);

        $this->value = $value;
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

    private function validate(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new EmailNotValid($value);
        }
    }
}

