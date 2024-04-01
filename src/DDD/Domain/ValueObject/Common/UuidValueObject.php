<?php

declare(strict_types=1);

namespace DDD\Domain\ValueObject\Common;

use DDD\Domain\Exceptions\InvalidUuid;
use Ramsey\Uuid\Uuid;

class UuidValueObject
{
    /** @var string */
    protected $value;

    public function __construct(string $value)
    {
        $this->validate($value);

        $this->value = $value;
    }

    public static function from(string $value) : self
    {
        return new static($value);
    }

    public function value() : string
    {
        return $this->value;
    }

    public function equals(UuidValueObject $other) : bool
    {
        return $this->value() === $other->value();
    }

    public function __toString() : string
    {
        return $this->value();
    }

    public function random() : self
    {
        return new static(Uuid::uuid4()->toString());
    }

    /**
     * @throws InvalidUuid
     */
    private function validate(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new InvalidUuid($value);
        }
    }
}
