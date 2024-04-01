<?php

namespace DDD\Infrastructure\UI\API\Controllers;

use DDD\Application\UseCase\Auth\LoginUseCase;
use DDD\Application\UseCase\Auth\LogoutUsecase;
use DDD\Application\UseCase\Auth\RegisterAdminUsecase;
use DDD\Application\UseCase\Auth\RegisterUsecase;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserPassword;
use DDD\Infrastructure\UI\API\Controllers\Common\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticationController extends BaseController
{
    private LoginUseCase $loginUsecase;
    private LogoutUsecase $logoutUsecase;
    private RegisterUsecase $registerUsecase;
    private RegisterAdminUsecase $adminRegisterUsecase;

    public function __construct(
        LoginUseCase $loginUsecase,
        LogoutUsecase $logoutUsecase,
        RegisterUsecase $registerUsecase,
        RegisterAdminUsecase $adminRegisterUsecase,
    )
    {
        $this->loginUsecase = $loginUsecase;
        $this->registerUsecase = $registerUsecase;
        $this->adminRegisterUsecase = $adminRegisterUsecase;
        $this->logoutUsecase = $logoutUsecase;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $response = $this->loginUsecase->execute(
            new UserEmail($request->email),
            new UserPassword($request->password)
        );

        return $this->commonJSONResponse($response);
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->commonJSONResponse(
            $this->logoutUsecase->execute()
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $response = $this->registerUsecase->execute(
            new UserEmail($request->email),
            new UserPassword($request->password),
            new UserPassword($request->password_confirmation)
        );

        return $this->commonJSONResponse($response);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function registerAdmin(Request $request): JsonResponse
    {
        $response = $this->adminRegisterUsecase->execute(
            $request->email,
            $request->password,
            $request->password_confirmation
        );

        return $this->commonJSONResponse($response);
    }
}
