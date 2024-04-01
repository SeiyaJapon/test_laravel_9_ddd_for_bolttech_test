<?php

declare(strict_types=1);

namespace DDD\Application\UseCase\Auth;

use DDD\Domain\Repository\AuthRepositoryInterface;
use DDD\Domain\Services\ValidationServiceInterface;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserPassword;

class LoginUseCase
{
    private AuthRepositoryInterface $authRepository;
    private ValidationServiceInterface $validationRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        ValidationServiceInterface $validationRepository
    )
    {
        $this->authRepository = $authRepository;
        $this->validationRepository = $validationRepository;
    }

    /**
     * @param UserEmail    $email
     * @param UserPassword $password
     *
     * @return array
     */
    public function execute(UserEmail $email, UserPassword $password): array
    {
        $this->validationRepository->validateCredentials($email, $password);

        $response = $this->authRepository->attempt($email, $password);

        return [
            array_key_first($response) => $response[array_key_first($response)],
            'code' => $response['code']
        ];
    }
}
