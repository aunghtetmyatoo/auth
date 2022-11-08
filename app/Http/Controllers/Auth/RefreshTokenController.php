<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Passport\PasswordGrant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RefreshToken\RefreshTokenRequest;
use App\Http\Resources\Api\Auth\PlayerResource;
use App\Models\User;
use App\Traits\Auth\ApiResponse;

class RefreshTokenController extends Controller
{
    use ApiResponse;
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RefreshTokenRequest $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        if (!isset($request->refresh_token)) {
            $refresh_token = $request->cookie('refresh_token');
            if ($refresh_token == null || $refresh_token == "") {
                return $this->responseSomethingWentWrong(message: "Something went wrong!");
            }
        }
        $refresh_token = $request->refresh_token;

        $response = (new PasswordGrant(user: $user))->refreshToken($refresh_token);
        if ($response->failed()) {
            return $this->responseSomethingWentWrong(message: $response->json()['message']);
        }
        $tokens = $response->json();

        if (isset($request->header()['user-agent'])) {
            $cookie = getUserCookie($tokens);
            if (config('app.env') === "production") {
                unset($tokens['refresh_token']);
            };
            return $this->responseSucceed(data: [
                'user' => new PlayerResource($user),
                'token' => $response->json()
            ], cookie: config('app.env') === "production" ? $cookie : null, message: 'Successfully Logged In');
        }

        return $this->responseSucceed(data: [
            'user' => new PlayerResource($user),
            'token' => $tokens
        ]);
    }
}
