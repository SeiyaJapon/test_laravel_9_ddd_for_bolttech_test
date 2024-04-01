<?php

declare(strict_types=1);

namespace DDD\Domain\Services;

use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserPassword;

interface ValidationServiceInterface
{
    public function validateCredentials(UserEmail $email, UserPassword $password): void;

    public function validateEmailAndPassword(UserEmail $email, UserPassword $password, UserPassword $repassword): void;
}
