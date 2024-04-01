<?php

declare(strict_types=1);

namespace DDD\Infrastructure\UI\Transformers;

use DateTime;
use DDD\Domain\Entities\User\User;
use DDD\Domain\ValueObject\User\UserActivationToken;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserId;
use DDD\Domain\ValueObject\User\UserPassword;
use DDD\Domain\ValueObject\User\UserResetPassword;
use Exception;

class UserEloquentTransform
{
    /**
     * @param array $data
     *
     * @return User
     *
     * @throws Exception
     */
    public static function __fromArray(array $data): User
    {
        $user = new User(
            new UserId($data['id']),
            new UserEmail($data['email']),
            key_exists('password', $data) ? new UserPassword($data['password']) : null
        );

        if (key_exists('email_verified_at', $data) && $data['email_verified_at']) {
            $user->setEmailVerifiedAt(new DateTime($data['email_verified_at']));
        }

        if ((key_exists('activation_token', $data))) {
            $user->setActivationToken(new UserActivationToken($data['activation_token']));
        }

        if (key_exists('reset_password', $data)) {
            $user->setResetPasswordToken(new UserResetPassword($data['reset_password']));
        }

        if (key_exists('license_expiration', $data) && $data['license_expiration']) {
            $user->setLicenseExpiration(new DateTime((string) $data['license_expiration']));
        }

        if (key_exists('created_at', $data) && $data['created_at']) {
            $user->setCreatedAt(new DateTime((string) $data['created_at']));
        }

        if (key_exists('updated_at', $data) && $data['updated_at']) {
            $user->setUpdatedAt(new DateTime((string) $data['updated_at']));
        }

        if (key_exists('setDeletedAt', $data) && $data['setDeletedAt']) {
            $user->setDeletedAt(new DateTime((string) $data['setDeletedAt']));
        }

        return $user;
    }
}
