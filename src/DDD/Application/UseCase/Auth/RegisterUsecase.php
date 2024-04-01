<?php

declare(strict_types=1);

namespace DDD\Application\UseCase\Auth;

use DDD\Domain\CodeValues;
use DDD\Domain\Exceptions\User\UserAlreadyExistsException;
use DDD\Domain\Repository\AuthRepositoryInterface;
use DDD\Domain\Repository\UserRepositoryInterface;
use DDD\Domain\Services\ValidationServiceInterface;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserPassword;

class RegisterUsecase
{
    private AuthRepositoryInterface $authRepository;
    private UserRepositoryInterface $userRepository;
    private ValidationServiceInterface $validationRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        UserRepositoryInterface $userRepository,
        ValidationServiceInterface $validationRepository
    )
    {
        $this->authRepository = $authRepository;
        $this->userRepository = $userRepository;
        $this->validationRepository = $validationRepository;
    }

    /**
     * @param UserEmail    $email
     * @param UserPassword $password
     * @param UserPassword $passwordConfirmation
     *
     * @return array
     */
    public function execute(
        UserEmail $email,
        UserPassword $password,
        UserPassword $passwordConfirmation,
    ): array
    {
        $this->validationRepository->validateEmailAndPassword(
            $email,
            $password,
            $passwordConfirmation
        );

        if (! $this->userRepository->findByEmail($email)) {
            return $this->authRepository->register($email, $password);
        }

        return ['message' => UserAlreadyExistsException::MESSAGE, 'code' => CodeValues::CREATED_CODE];
    }
}
