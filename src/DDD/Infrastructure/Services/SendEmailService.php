<?php

declare(strict_types=1);

namespace DDD\Infrastructure\Services;

use App\Jobs\RequestResetPasswordJob;
use App\Jobs\SendActivationTokenJob;
use DDD\Domain\Exceptions\User\UserAlreadyHasBeenActivatedException;
use DDD\Domain\Repository\UserRepositoryInterface;
use DDD\Domain\Services\SendEmailServiceInterface;
use DDD\Domain\ValueObject\User\UserId;
use DDD\Domain\ValueObject\User\UserResetPassword;

class SendEmailService implements SendEmailServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserId $userId
     *
     * @throws UserAlreadyHasBeenActivatedException
     */
    public function sendActivationEmail(UserId $userId): void
    {
        if (false === env('APP_DEBUG')) {
            $user = $this->userRepository->findByIdOrFail($userId);

            if ($user && !$this->userRepository->isActive($userId)) {
                dispatch(
                    new SendActivationTokenJob(
                        json_encode(
                            [
                                'user_id' => $user->getId()->value(),
                                'activation_token' => $user->getActivationToken()->value()
                            ]
                        )
                    )
                );
            } elseif ($this->userRepository->isActive($userId)) {
                abort(200, UserAlreadyHasBeenActivatedException::MESSAGE);
            }
        }
    }

    /**
     * @param UserId            $userId
     * @param UserResetPassword $resetPassword
     */
    public function sendRequestResetPassword(UserId $userId, UserResetPassword $resetPassword): void
    {
        if (false === env('APP_DEBUG')) {
            if ($this->userRepository->findByResetPasswordOrFail($userId, $resetPassword)) {
                dispatch(
                    new RequestResetPasswordJob(
                        json_encode(
                            [
                                'user_id' => $userId->value(),
                                'reset_token' => $resetPassword->value()
                            ]
                        )
                    )
                );
            }
        }
    }
}
