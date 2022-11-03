<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Passport\PasswordGrant;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Auth\PlayerResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\Auth\ApiResponse;

class LoginController extends Controller
{
    use ApiResponse;

    public function playerLogin(Request $request)
    {
        $user = User::where("phone_number", $request->phone_number)->first();
        if (!$user) {
            return $this->responseSomethingWentWrong();
        }
        if (!Hash::check($request->password, $user->password)) {
            return $this->responseSomethingWentWrong(message: "Incorrect Password");
        }
        $response = (new PasswordGrant(user: $user))->execute(request: $request);
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

    public function logout()
    {
        if (auth()->user()->token()->revoke()) {
            return $this->responseSucceed(message: "Logout Successfully");
        }
    }
}
