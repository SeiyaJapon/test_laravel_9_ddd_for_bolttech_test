<?php

declare(strict_types=1);

namespace DDD\Infrastructure\Services;

use DDD\Domain\Exceptions\User\UserCredentialsValidationException;
use DDD\Domain\Services\ValidationServiceInterface;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserPassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ValidationService implements ValidationServiceInterface
{
    public const NO_ROLE_ARG = 'You have not included argument #5. It must be of type string. Null given';
    /**
     * @param UserEmail    $email
     * @param UserPassword $password
     *
     * @throws UserCredentialsValidationException
     */
    public function validateCredentials(UserEmail $email, UserPassword $password): void
    {
        $credentials = [
            'email' => $email->value(),
            'password' => $password->value()
        ];

        $validator = Validator::make($credentials, [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            throw new UserCredentialsValidationException($validator->messages()->first());
        }
    }

    /**
     * @param UserEmail    $email
     * @param UserPassword $password
     * @param UserPassword $repassword
     *
     * @throws UserCredentialsValidationException
     */
    public function validateEmailAndPassword(UserEmail $email, UserPassword $password, UserPassword $repassword): void
    {
        $data = [
            'email' => $email->value(),
            'password' => $password->value(),
            'password_confirmation' => $repassword->value()
        ];

        $validator = Validator::make($data, [
            'email' => 'required|string|email',
            'password' => ['required', 'confirmed', Password::min(6)]
        ]);

        if ($validator->fails()) {
            throw new UserCredentialsValidationException($validator->messages()->first());
        }
    }
}
