<?php

declare(strict_types=1);


namespace DDD\Domain\Repository;

use DDD\Domain\Entities\User\User;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserId;
use DDD\Domain\ValueObject\User\UserPassword;

interface UserRepositoryInterface
{
    public function findById(UserId $id): ?User;

    public function findByIdOrFail(UserId $id): ?User;

    public function findByEmail(UserEmail $email): ?User;

    public function findByEmailOrFail(UserEmail $email): ?User;

    public function save(UserEmail $email, UserPassword $password): ?User;

    public function delete(UserId $userId): void;

    public function isAdmin(UserId $userId): bool;

    public function isActive(UserId $userId): bool;

    public function isUserLicenseValidBetweenDates(User $user, \DateTime $end): bool;
}
