<?php

declare(strict_types=1);

namespace DDD\Domain\ValueObject\Common;

class CurrencyValueObject
{
    private int $value;
    private string $currency;

    public function __construct(
        int $value,
        string $currency = 'EUR'
    ){
        $this->value = $value;
        $this->currency = $currency;
    }

    public function equals(CurrencyValueObject $currency): bool
    {
        return $this->value === $currency->value
            && $this->currency === $currency->currency;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function currency(): string
    {
        return $this->currency;
    }
}
