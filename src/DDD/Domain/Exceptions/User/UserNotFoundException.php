<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\User;

use DDD\Domain\Exceptions\Common\DomainException;

final class UserNotFoundException extends DomainException {
    public const MESSAGE_USER_NOT_FOUND = "User with email %s not found";

    /**
     * UserNotFoundByIdException constructor.
     *
     * @param string $email
     */
    public function __construct(string $email)
    {
        parent::__construct(
            sprintf(
                self::MESSAGE_USER_NOT_FOUND,
                $email
            )
        );
    }
}
