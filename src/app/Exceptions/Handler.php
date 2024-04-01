<?php

namespace App\Exceptions;

use DDD\Domain\Exceptions\Libraries\ErrorLibraryCodes;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use PDOException;
use Throwable;
use TypeError;

class Handler extends ExceptionHandler
{
    private const CONTENT_TYPE_APPLICATION_JSON = 'application/json';

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param Request   $request
     * @param Throwable $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (self::CONTENT_TYPE_APPLICATION_JSON === $request->header('Content-Type')) {
            if (!empty($exception)) {
                $status = null;
                $response = [
                    'error' => 'Sorry, can not execute your request.'
                ];

                $response['message'] = $exception->getMessage();
                $response['intCode'] = ErrorLibraryCodes::GENERAL_0001;

                extract($this->manageException($exception, $response, $request));

                if (config('app.debug')) {
                    $response['exception'] = get_class($exception); // Reflection might be better here
                    $response['trace'] = $exception->getTrace();
                }

                return response()->json($response, $status);
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['error' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Throwable $exception
     * @param array     $response
     * @param Request   $request
     *
     * @return array|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    private function manageException(Throwable $exception, array $response, Request $request)
    {
        $status = 400;

        switch ($exception) {
            case $exception instanceof ValidationException:
                return $this->convertValidationExceptionToResponse($exception, $request);

            case $exception instanceof AuthenticationException:
                $status = 401;

                $response['error'] = 'Can not finish authentication.';
                $response['error'] .= $request->bearerToken() ? '' : ' No has BEARER TOKEN!';

                $response['intCode'] = $request->bearerToken() ?
                    ErrorLibraryCodes::AUTH_0001 :
                    ErrorLibraryCodes::AUTH_0002;

                break;

            case $exception instanceof PDOException:
                $status = Response::HTTP_BAD_REQUEST;

                $response['error'] = 'Can not finish your query request!';
                $response['intCode'] = ErrorLibraryCodes::REQUEST_0001;

                break;

            case $exception instanceof TypeError:
                $response['error'] .= 'Type Error Exception';
                $response['intCode'] = (new ErrorLibraryCodes())->catchTypeErrorCode($response);

                if (str_contains($exception->getMessage(), 'must be of type') &&
                    str_contains($exception->getMessage(), ', called in')) {
                    $response['message'] = explode(
                        ', called in',
                        explode(': ', $exception->getMessage())[1]
                    )[0];
                }

                break;

            case $this->isHttpException($exception):
                $status = $exception->getStatusCode();

                $response['error'] = 'Request error!';
                $response['intCode'] = ErrorLibraryCodes::GENERAL_0002;

                break;

            default:
                $status = method_exists($exception, 'getStatusCode')
                    ? $exception->getStatusCode()
                    : Response::HTTP_BAD_REQUEST;

                break;
        }

        return ['response' => $response, 'status' => $status];
    }
}
