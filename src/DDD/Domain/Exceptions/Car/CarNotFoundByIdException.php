<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\Car;

use DDD\Domain\Exceptions\Common\DomainException;

final class CarNotFoundByIdException extends DomainException
{
    /**
     * CarNotFoundByIdException constructor.
     *
     * @param string $id
     */
    public function __construct(
        string $id
    ) {
        parent::__construct(
            sprintf(
                "Car with id %s not found",
                $id
            )
        );
    }
}
