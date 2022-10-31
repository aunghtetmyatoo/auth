<?php

namespace App\Traits\Gambling;

use Symfony\Component\HttpFoundation\Response;
use App\Traits\Auth\AuthResponse;
use Illuminate\Http\JsonResponse;

trait GamblingResponse
{
    use AuthResponse;

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
}
