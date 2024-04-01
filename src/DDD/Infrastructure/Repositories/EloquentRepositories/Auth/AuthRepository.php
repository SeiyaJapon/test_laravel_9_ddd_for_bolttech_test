<?php

declare(strict_types=1);

namespace DDD\Infrastructure\Repositories\EloquentRepositories\Auth;

use DDD\Domain\CodeValues;
use DDD\Domain\Entities\User\User;
use DDD\Domain\Exceptions\User\UserAlreadyExistsException;
use DDD\Domain\Exceptions\User\UserAlreadyHasBeenActivatedException;
use DDD\Domain\RegisterType;
use DDD\Domain\Repository\AuthRepositoryInterface;
use DDD\Domain\Repository\UserRepositoryInterface;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserId;
use DDD\Domain\ValueObject\User\UserPassword;
use DDD\Infrastructure\Services\SendEmailService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface
{
    public const REGISTER_SUCCESS = 'User created. Register Success';
    public const NOT_REGISTERED = 'User not registered. Something is wrong. Check your data.';

    private UserRepositoryInterface $userRepository;
    private SendEmailService $sendEmailService;

    public function __construct(UserRepositoryInterface $userRepository, SendEmailService $sendEmailService)
    {
        $this->userRepository = $userRepository;
        $this->sendEmailService = $sendEmailService;
    }

    /**
     * @param UserEmail    $email
     * @param UserPassword $password
     * @return array
     */
    public function attempt(UserEmail $email, UserPassword $password): array
    {
        $credentials = [
            'email' => $email->value(),
            'password' => $password->value()
        ];

        if (Auth::attempt($credentials)) {
            return [
                'token' => Auth::user()->createToken('Token Name')->accessToken,
                'code' => Response::HTTP_ACCEPTED
            ];
        }

        return [
            'message' => 'Unauthorized',
            'code' => Response::HTTP_UNAUTHORIZED
        ];
    }

    public function logout(): void
    {
        if (Auth::check()) {
            Auth::user()->AuthAccessToken()->delete();
        }
    }

    /**
     * @param UserEmail    $email
     * @param UserPassword $password
     *
     * @return array
     *
     * @throws UserAlreadyHasBeenActivatedException
     */
    public function register(UserEmail $email, UserPassword $password): array
    {
        if ($user = $this->userRegister($email, $password)) {
            $this->sendEmailService->sendActivationEmail(new UserId($user->getId()->value()));

            return [
                'message' => self::REGISTER_SUCCESS,
                'code' => Response::HTTP_CREATED
            ];
        }

        return [
            'message' => self::NOT_REGISTERED,
            'code' => Response::HTTP_NOT_ACCEPTABLE
        ];
    }

    /**
     * @param UserEmail    $email
     * @param UserPassword $password
     *
     * @return array
     * @throws UserAlreadyHasBeenActivatedException
     */
    public function registerAdmin(UserEmail $email, UserPassword $password): array
    {
        if ($user = $this->userRegister($email, $password)) {
            $this->sendEmailService->sendActivationEmail(new UserId($user->getId()->value()));

            return [
                'message' => self::REGISTER_SUCCESS,
                'code' => Response::HTTP_CREATED
            ];
        }

        return [
            'message' => self::NOT_REGISTERED,
            'code' => Response::HTTP_NOT_ACCEPTABLE
        ];
    }

    /**
     * @param UserEmail    $email
     * @param UserPassword $password
     *
     * @return User
     */
    private function userRegister(UserEmail $email, UserPassword $password): User
    {
        return $this->userRepository->save($email, $password);
    }

    /**
     * @param string|null $role
     *
     * @return bool
     */
    private function checkUserRole(string $role = null): bool
    {
        return $role && collect(Auth::user()->roles->first())->contains($role);
    }
}
