<?php

declare(strict_types=1);

namespace DDD\Domain\Services;

use DDD\Domain\ValueObject\User\UserId;
use DDD\Domain\ValueObject\User\UserResetPassword;

interface SendEmailServiceInterface
{
    public function sendActivationEmail(UserId $userId): void;

    public function sendRequestResetPassword(UserId $userId, UserResetPassword $resetPassword): void;
}
