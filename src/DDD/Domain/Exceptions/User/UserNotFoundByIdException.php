<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\User;

use DDD\Domain\Exceptions\Common\DomainException;

final class UserNotFoundByIdException extends DomainException {
    /**
     * UserNotFoundByIdException constructor.
     *
     * @param string $id
     */
    public function __construct(
        string $id
    ) {
        parent::__construct(
            sprintf(
                "User with id %s not found",
                $id
            )
        );
    }
}
