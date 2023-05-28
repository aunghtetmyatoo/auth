<?php

namespace App\Traits\Auth;

use App\Services\Crypto\DataKey;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    public function encrypt(object $data)
    {
        return $data;
        if (config('app.crypto')) {
            return (new DataKey())->encrypt(json_encode($data));
        }

        return $data;
    }

    public function responseValidationErrors(array $errors = [], string $message = null): string | JsonResponse
    {
        return $this->encrypt(response()->json([
            'data'    => [
                ...$errors,
                'message' => $message ? $message : 'Validation error.',
            ],
            'code'    => Response::HTTP_UNPROCESSABLE_ENTITY,
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    public function responseUnprocessableEntity(string $message = '', bool $translate = true): string | JsonResponse
    {
        if ($message && $translate) {
            $message = trans($message);
        }

        return $this->encrypt(response()->json([
            'data'    => [
                ...$this->translate(message: $message, code: Response::HTTP_UNPROCESSABLE_ENTITY),
            ],
            'code'    => Response::HTTP_UNPROCESSABLE_ENTITY,
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    public function responseUnauthenticated(string $message = '', array $replace = [],  $status_code = Response::HTTP_UNAUTHORIZED): string | JsonResponse
    {
        return $this->encrypt(response()->json([
            'data'    => [
                ...$this->translate(message: $message, replace: $replace, code: $status_code),
            ],
            'code'    => $status_code,
        ], $status_code));
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

    public function responseSucceed(array $data = [], $status_code = Response::HTTP_OK, string $message = '', $cookie = null): string | JsonResponse
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

        return $this->encrypt($response);
    }

    public function responseSomethingWentWrong(string $message = ''): string | JsonResponse
    {
        return $this->encrypt(response()->json([
            'data'    => [
                ...$this->translate(message: $message, code: Response::HTTP_INTERNAL_SERVER_ERROR),
            ],
            'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
        ], Response::HTTP_INTERNAL_SERVER_ERROR));
    }

    public function responseWithCustomErrorCode(string $message = '', $status_code = Response::HTTP_ACCEPTED): string | JsonResponse
    {
        return $this->encrypt(response()->json([
            'data'    => [
                ...$this->translate(message: $message, code: $status_code),
            ],
            'code'    => $status_code,
        ], $status_code));
    }

    public function responseCollection(mixed $data, $status_code = Response::HTTP_OK): string | JsonResponse
    {
        return $this->encrypt(response()->json([
            'data'    => [
                ...$data,
            ],
            'code'    => $status_code,
        ], 200));
    }

    public function responseResource(mixed $data, $status_code = Response::HTTP_OK): string | JsonResponse
    {
        return $this->encrypt(response()->json([
            'data'    => [
                $data,
            ],
            'code'    => $status_code,
        ], 200));
    }

    public function responseBadRequest(string $message = '', $status_code = Response::HTTP_BAD_REQUEST): string | JsonResponse
    {
        return $this->encrypt(response()->json([
            'data'    => [
                ...$this->translate(message: $message, code: $status_code),
            ],
            'code'    => $status_code,
        ], $status_code));
    }
}
