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

class RegisterAdminUsecase
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
     * @param string $email
     * @param string $password
     * @param string $passwordConfirmation
     *
     * @return array
     */
    public function execute(string $email, string $password, string $passwordConfirmation)
    {
        $email = new UserEmail($email);
        $password = new UserPassword($password);
        $passwordConfirmation = new UserPassword($passwordConfirmation);

        $this->validationRepository->validateEmailAndPassword(
            $email,
            $password,
            $passwordConfirmation
        );

        if (! $this->userRepository->findByEmail($email)) {
            return $this->authRepository->registerAdmin($email, $password);
        }

        return ['message' => UserAlreadyExistsException::MESSAGE, 'code' => CodeValues::BAD_REQUEST];
    }
}
