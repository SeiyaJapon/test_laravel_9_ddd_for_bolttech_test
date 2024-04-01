<?php

declare(strict_types=1);

namespace DDD\Domain\ValueObject\Common;

use DateInterval;
use DateTime;
use DateTimeInterface;

class DatetimeNullableValueObject
{
    protected $value;

    public function __construct(?Datetime $value)
    {
        $this->value = $value;
    }

    public static function from(?Datetime $value): self
    {
        return new static($value);
    }

    public static function fromString(?string $value): self
    {
        return self::from($value ? new Datetime($value) : null);
    }

    public static function now(): self
    {
        return self::fromString('now');
    }

    public function value(): ?DateTimeInterface
    {
        return $this->value;
    }

    public function format(string $format): string
    {
        return $this->value->format($format);
    }

    public function iso8601(): ?string
    {
        return $this->value
            ? $this->value->format(DateTime::ISO8601)
            : null;
    }

    public function lessThan(DateTime $other): bool
    {
        if (!$this->value) {
            return false;
        }

        return $this->value < $other;
    }

    public function greaterThan(DateTime $other): bool
    {
        if (!$this->value) {
            return false;
        }

        return $this->value > $other;
    }

    public function equals(DateTime $other): bool
    {
        if (!$this->value) {
            return false;
        }

        return $this->value == $other;
    }

    public function lessThanToday(): bool
    {
        if (!$this->value) {
            return false;
        }

        return $this->lessThan(new DateTime());
    }

    public function lessThanYesterday(): bool
    {
        if (!$this->value) {
            return false;
        }

        return $this->lessThan((new DateTime())->sub(new DateInterval('P1D')));
    }

    public function greaterThanToday(): bool
    {
        if (!$this->value) {
            return false;
        }

        return $this->greaterThan(new DateTime());
    }

    public function greaterThanYesterday(): bool
    {
        if (!$this->value) {
            return false;
        }

        return $this->greaterThan((new DateTime())->sub(new DateInterval('P1D')));
    }

    public function equalsToday(): bool
    {
        if (!$this->value) {
            return false;
        }

        return $this->equals(new DateTime());
    }

    public function isNull(): bool
    {
        return $this->value === null;
    }
}

