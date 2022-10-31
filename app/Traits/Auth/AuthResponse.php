<?php

namespace App\Traits\Auth;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait AuthResponse
{
    public function responseUnauthenticated(string $message = '', array $replace = [],  $status_code = Response::HTTP_UNAUTHORIZED): JsonResponse
    {
        return response()->json([
            'data'    => [
                ...$this->translate(message: $message, replace: $replace, code: $status_code),
            ],
            'code'    => $status_code,
        ], 200);
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
}
