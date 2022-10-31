<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\AccessToken;
use App\Traits\Auth\AuthResponse;
use App\Traits\Gambling\GamblingResponse;
use App\Http\Requests\Api\Auth\Login\GetOtpRequest;
use App\Services\Auth\OneTimePassword;
use App\Enums\OtpAction;

class RegisterController extends Controller
{
    use AuthResponse, GamblingResponse;

    public function __construct(protected AccessToken $accessToken)
    {
    }

    public function getOtp(GetOtpRequest $request)
    {
        (new OneTimePassword(
            phone_number: $request->phone_number,
            browser_id: $request->browser_id,
        ))->send(user: null, action: OtpAction::Register, life_time: config('auth.otp.expires.mb_register'));

        return $this->responseSucceed(data: [
            'token' => $this->accessToken->generate($request->phone_number, 'mb_register_verify_otp'),
        ], message: 'otp.sent');
    }


    public function verifyOpt()
    {
    }

    public function register()
    {
    }
}
