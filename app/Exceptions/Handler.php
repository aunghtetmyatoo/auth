<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\Auth\ApiResponse;
use App\Exceptions\UnprocessableException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ApiResponse;
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

    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $exception) {
            // Kill reporting if this is an "access denied" (code 9) OAuthServerException.
            if ($exception instanceof \League\OAuth2\Server\Exception\OAuthServerException && $exception->getCode() == 9) {
                return;
            }

            parent::report($exception);
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof GeneralError) {
            return $this->responseUnprocessableEntity(message: 'Cannot Perform Task.');
        }

        /**
         *  Development validation exception
         */
        if ($exception instanceof UnprocessableException) {
            if (config('app.dev_validator')) {
                return $this->responseValidationErrors(errors: $exception->errors);
            }
            return $this->responseUnprocessableEntity(message: 'Missing required parameters.');
        }

        if ($exception instanceof OtpInvalidException) {
            return $this->responseUnauthenticated(
                message: $exception->getMessage() ? $exception->getMessage() : 'otp.invalid'
            );
        }
        if ($exception instanceof PasswordInvalidException) {
            return $this->responseUnauthenticated(
                message: $exception->getMessage() ? $exception->getMessage() : 'passwords.invalid'
            );
        }
        /**
         *  Laravel form request validation exception
         */
        if ($exception instanceof ValidationException) {
            return $this->responseValidationErrors(errors: json_decode($exception->validator->errors(), true), message: $exception->getMessage());
        }

        return parent::render($request, $exception);
    }
}
