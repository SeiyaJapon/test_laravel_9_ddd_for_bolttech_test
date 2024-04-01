<?php

declare(strict_types=1);

namespace DDD\Domain\Repository;

use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserPassword;

interface AuthRepositoryInterface
{
    public function attempt(UserEmail $email, UserPassword $password): array;

    public function logout(): void;

    public function register(UserEmail $email, UserPassword $password): array;

    public function registerAdmin(UserEmail $email, UserPassword $password): array;
}
