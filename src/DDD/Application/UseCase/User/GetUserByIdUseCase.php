<?php

declare(strict_types=1);

namespace DDD\Application\UseCase\User;

use DDD\Application\Exceptions\Common\ApplicationException;
use DDD\Domain\Entities\User\User;
use DDD\Domain\Repository\UserRepositoryInterface;
use DDD\Domain\ValueObject\User\UserId;

class GetUserByIdUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserId $id
     *
     * @return User
     *
     * @throws ApplicationException
     */
    public function execute(UserId $id): User
    {
        try {
            return $this->userRepository->findByIdOrFail($id);
        } catch (\Exception $exception) {
            throw new ApplicationException($exception->getMessage());
        }
    }
}
