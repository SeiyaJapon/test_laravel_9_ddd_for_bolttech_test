<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\User;

use DDD\Domain\Exceptions\Common\DomainException;

class UserCredentialsValidationException extends DomainException
{
    public function __construct($message = '')
    {
        parent::__construct($message);
    }
}
