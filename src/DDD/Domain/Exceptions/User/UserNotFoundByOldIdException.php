<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\User;

use DDD\Domain\Exceptions\Common\DomainException;

final class UserNotFoundByOldIdException extends DomainException {
    const USER_NOT_FOUND_MESSAGE = "User with old id %s not found";

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
                self::USER_NOT_FOUND_MESSAGE,
                $id
            )
        );
    }
}
