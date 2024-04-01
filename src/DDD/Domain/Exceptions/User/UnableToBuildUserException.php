<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\User;

use DDD\Domain\Exceptions\Common\DomainException;

final class UnableToBuildUserException extends DomainException {
    public const MESSAGE = 'Unable to build user';
}
