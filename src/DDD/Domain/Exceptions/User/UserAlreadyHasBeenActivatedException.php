<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\User;

use DDD\Domain\Exceptions\Common\DomainException;

final class UserAlreadyHasBeenActivatedException extends DomainException {
    public const MESSAGE = 'User already has been activated';

    public function __construct() {
        parent::__construct(self::MESSAGE);
    }
}
