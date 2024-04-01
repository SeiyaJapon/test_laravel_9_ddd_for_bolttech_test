<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\User;

use DDD\Domain\Exceptions\Common\DomainException;

final class UserNotFoundByEmailAndException extends DomainException {
    const USER_NOT_FOUND_MESSAGE = "User with email %s not found";

    /**
     * UserNotFoundByIdException constructor.
     *
     * @param string $email
     */
    public function __construct(string $email) {
        parent::__construct(
            sprintf(
                self::USER_NOT_FOUND_MESSAGE,
                $email
            )
        );
    }
}
