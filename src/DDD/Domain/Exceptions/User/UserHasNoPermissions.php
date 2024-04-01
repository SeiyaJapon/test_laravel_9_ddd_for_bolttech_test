<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\User;

use DDD\Domain\Exceptions\Common\DomainException;
use Throwable;

class UserHasNoPermissions extends DomainException
{
    const NO_PERMISSIONS_MESSAGE = 'Authenticated user has no permissions for this action';

    public function __construct()
    {
        parent::__construct(self::NO_PERMISSIONS_MESSAGE);
    }
}
