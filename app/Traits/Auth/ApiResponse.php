<?php

namespace App\Traits\Auth;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    public function responseValidationErrors(array $errors = [], string $message = null)
    {
        return response()->json([
            'data'    => [
                ...$errors,
                'message' => $message ? $message : 'Validation error.',
            ],
            'code'    => Response::HTTP_UNPROCESSABLE_ENTITY,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function responseUnprocessableEntity(string $message = '', bool $translate = true): JsonResponse
    {
        if ($message && $translate) {
            $message = trans($message);
        }
        return response()->json([
            'data'    => [
                ...$this->translate(message: $message, code: Response::HTTP_UNPROCESSABLE_ENTITY),
            ],
            'code'    => Response::HTTP_UNPROCESSABLE_ENTITY,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function responseUnauthenticated(string $message = '', array $replace = [],  $status_code = Response::HTTP_UNAUTHORIZED): JsonResponse
    {
        return response()->json([
            'data'    => [
                ...$this->translate(message: $message, replace: $replace, code: $status_code),
            ],
            'code'    => $status_code,
        ], $status_code);
    }

    private function getDefaultResponses()
    {
        return config('response.map');
    }


    private function translate(string $message, array $replace = [], int $code = null): array
    {
        return [
            'message' => $message ? trans($message, $replace) : trans($this->getDefaultResponses()[$code])
        ];
    }
    public function responseSucceed(array $data = [], $status_code = Response::HTTP_OK, string $message = '', $cookie = null): JsonResponse
    {
        $response = response()->json([
            'data'    => [
                ...$data,
                ...$this->translate(message: $message, code: Response::HTTP_OK),
            ],
            'code'    => $status_code,
        ], 200);

        if ($cookie) {
            $response->withCookie($cookie);
        }

        return $response;
    }

    public function responseSomethingWentWrong(string $message = ''): JsonResponse
    {
        $response = response()->json([
            'data'    => [
                ...$this->translate(message: $message, code: Response::HTTP_INTERNAL_SERVER_ERROR),
            ],
            'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

    public function responseWithCustomErrorCode(string $message = '', $status_code = Response::HTTP_ACCEPTED): JsonResponse
    {
        $response = response()->json([
            'data'    => [
                ...$this->translate(message: $message, code: $status_code),
            ],
            'code'    => $status_code,
        ], $status_code);
        return $response;
    }
}
