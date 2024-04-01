<?php

declare(strict_types=1);

namespace DDD\Domain\ValueObject\Common;

use Exception;

class BooleanValueObject
{
    protected bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function __toInt()
    {
        return (int) $this->value();
    }
}
