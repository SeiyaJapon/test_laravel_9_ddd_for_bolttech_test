<?php

declare(strict_types=1);

namespace DDD\Application\UseCase\Auth;

use DDD\Domain\CodeValues;
use DDD\Domain\Repository\AuthRepositoryInterface;

class LogoutUsecase
{
    const LOGOUT_SUCCESS_MESSAGE = 'Logout success';
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @return array
     */
    public function execute(): array
    {
        $this->authRepository->logout();

        return ['message' => self::LOGOUT_SUCCESS_MESSAGE, 'code' => CodeValues::BAD_REQUEST];
    }
}
