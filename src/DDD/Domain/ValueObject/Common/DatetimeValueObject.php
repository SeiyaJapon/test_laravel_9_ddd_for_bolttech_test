<?php

declare(strict_types=1);

namespace DDD\Domain\ValueObject\Common;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class DatetimeValueObject
{
    protected $value;

    public function __construct(DateTimeImmutable $value)
    {
        $this->value = $value;
    }

    public static function from(DateTimeImmutable $value): self
    {
        return new static($value);
    }

    public static function fromString(string $value): self
    {
        return self::from(new DateTimeImmutable($value));
    }

    public static function now(): self
    {
        return self::fromString('now');
    }

    public function value() : DateTimeInterface
    {
        return $this->value;
    }

    public function format(string $format): string
    {
        return $this->value->format($format);
    }

    public function iso8601(): string
    {
        return $this->value->format(DateTime::ISO8601);
    }

    public function lessThan(DateTime $other): bool
    {
        return $this->value < $other;
    }

    public function greaterThan(DateTime $other): bool
    {
        return $this->value > $other;
    }

    public function equals(DateTime $other): bool
    {
        return $this->value == $other;
    }

    public function lessThanToday(): bool
    {
        return $this->lessThan(new DateTime());
    }

    public function lessThanYesterday(): bool
    {
        return $this->lessThan((new DateTime())->sub(new \DateInterval('P1D')));
    }

    public function greaterThanToday(): bool
    {
        return $this->greaterThan(new DateTime());
    }

    public function greaterThanYesterday(): bool
    {
        return $this->greaterThan((new DateTime())->sub(new \DateInterval('P1D')));
    }

    public function equalsToday(): bool
    {
        return $this->equals(new DateTime());
    }
}
