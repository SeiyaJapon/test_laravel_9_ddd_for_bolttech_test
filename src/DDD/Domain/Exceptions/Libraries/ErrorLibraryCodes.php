<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\Libraries;

use DDD\Domain\ValueObject\User\UserActivationToken;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserEmailVerification;
use DDD\Domain\ValueObject\User\UserId;
use DDD\Domain\ValueObject\User\UserPassword;
use DDD\Domain\ValueObject\User\UserResetPassword;

class ErrorLibraryCodes
{
    // Internal path to ValueObjects
    const DDD_DOMAIN_VALUE_OBJECT_BASE = 'DDD\Domain\ValueObject\\';
    const DDD_DOMAIN_VALUE_OBJECT_COMMON = 'DDD\Domain\ValueObject\Common\\';
    const DDD_DOMAIN_VALUE_OBJECT_PROJECT = 'DDD\Domain\ValueObject\Project\\';
    const DDD_DOMAIN_VALUE_OBJECT_USER = 'DDD\Domain\ValueObject\USER\\';

    // Internal path to Usecases
    const DDD_DOMAIN_USER_USECASES = 'DDD\Application\UseCase\User\\';
    const DDD_DOMAIN_AUTH_USECASES = 'DDD\Application\UseCase\Auth\\';

    // Internal path to Eloquent Repositories
    const DDD_DOMAIN_USER_ELOQUENT_REPO = 'DDD\Infrastructure\Repositories\EloquentRepositories\Auth\\';
    const DDD_DOMAIN_AUTH_ELOQUENT_REPO = 'DDD\Infrastructure\Repositories\EloquentRepositories\User\\';

    /************************
     *        GENERAL       *
     ************************/

    // Common mistake. There was an unspecified problem that prevented the request from being completed.
    public const GENERAL_0001 = 'GEN-0001';

    // Common HTTP error. There was an unspecified problem that prevented the HTTP request from completing.
    public const GENERAL_0002 = 'GEN-0002';

    /************************
     *      AUTH ERROR      *
     ************************/

    // Common authentication error. There was an unspecified problem that prevented the user from completing authentication.
    public const AUTH_0001 = 'AUTH-0001';

    // Common authentication error. Not has bearer token.
    public const AUTH_0002 = 'AUTH-0002';

    /************************
     *    REQUEST ERROR     *
     ************************/

    // Common mistake in the request. There was an unspecified problem that prevented the database request from completing.
    public const REQUEST_0001 = 'REQ-0001';

    /************************
     *      TYPE ERROR      *
     ************************/
    // Common mistake in the argument type.
    public const TYPE_0001 = 'TPE-0001';

    // Argument must be UserId type.
    public const TYPE_0002 = 'TPE-0002';

    // Argument must be UserEmail type.
    public const TYPE_0003 = 'TPE-0003';

    // Argument must be UserEmailVerification type.
    public const TYPE_0004 = 'TPE-0004';

    // Argument must be UserPassword type.
    public const TYPE_0005 = 'TPE-0005';

    // Argument must be UserResetPassword type.
    public const TYPE_0006 = 'TPE-0006';

    // Argument must be UserActivationToken type.
    public const TYPE_0007 = 'TPE-0007';


    /************************
     ************************
     *     CATCH ERRORS     *
     ************************
     ************************/
    public function catchTypeErrorCode(array $response): ?string
    {
        $value = $this->generateValue($response['message']);

        return match ($value) {
            UserId::class => self::TYPE_0002,
            UserEmail::class => self::TYPE_0003,
            UserEmailVerification::class => self::TYPE_0004,
            UserPassword::class => self::TYPE_0005,
            UserResetPassword::class => self::TYPE_0006,
            UserActivationToken::class => self::TYPE_0007,
            default => self::TYPE_0001,
        };
    }

    /**
     * @param string|null $message
     *
     * @return string|null
     */
    private function generateValue(string $message = null): ?string {
        if ($message) {
            switch ($message) {
                case str_contains($message, self::DDD_DOMAIN_VALUE_OBJECT_COMMON):
                    $path = self::DDD_DOMAIN_VALUE_OBJECT_COMMON;

                    break;

                case str_contains($message, self::DDD_DOMAIN_VALUE_OBJECT_PROJECT):
                    $path = self::DDD_DOMAIN_VALUE_OBJECT_PROJECT;

                    break;

                case str_contains($message, self::DDD_DOMAIN_VALUE_OBJECT_USER):
                    $path = self::DDD_DOMAIN_VALUE_OBJECT_USER;

                    break;

                case str_contains($message, self::DDD_DOMAIN_USER_USECASES):
                    $path = self::DDD_DOMAIN_USER_USECASES;

                    break;

                case str_contains($message, self::DDD_DOMAIN_AUTH_USECASES):
                    $path = self::DDD_DOMAIN_AUTH_USECASES;

                    break;

                case str_contains($message, self::DDD_DOMAIN_USER_ELOQUENT_REPO):
                    $path = self::DDD_DOMAIN_USER_ELOQUENT_REPO;

                    break;

                case str_contains($message, self::DDD_DOMAIN_AUTH_ELOQUENT_REPO):
                    $path = self::DDD_DOMAIN_AUTH_ELOQUENT_REPO;

                    break;

                default:
                    $path = str_contains($message, self::DDD_DOMAIN_VALUE_OBJECT_BASE) ?
                        self::DDD_DOMAIN_VALUE_OBJECT_BASE :
                        '';

                    break;
            }

            if ($path) {
                $message = explode(
                    ',',
                    explode($path, $message)[1]
                )[0];
                $message = $path . $message;
                $message = explode('::', $message)[0];
            }
        }

        return $message;
    }
}
