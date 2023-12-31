<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Enums\Language;
use App\Enums\OtpAction;
use App\Enums\UserPrefix;
use Illuminate\Support\Str;
use App\Models\PlayerSetting;
use App\Actions\UserReference;
use App\Traits\Auth\ApiResponse;
use App\Services\Auth\AccessToken;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\Auth\OneTimePassword;
use App\Actions\Passport\PasswordGrant;
use App\Http\Resources\Api\Auth\PlayerResource;
use App\Http\Requests\Api\Auth\Register\GetOtpRequest;
use App\Http\Requests\Api\Auth\Register\VerifyOtpRequest;
use App\Http\Requests\Api\Auth\Register\PlayerRegisterRequest;

class RegisterController extends Controller
{
    use ApiResponse;

    public function __construct(protected AccessToken $accessToken)
    {
    }

    public function getOtp(GetOtpRequest $request)
    {
        (new OneTimePassword(
            phone_number: $request->phone_number,
            device_id: $request->device_id,
        ))->send(user: null, action: OtpAction::Register, life_time: config('auth.otp.expires.mb_register'));

        return $this->responseSucceed(data: [
            'token' => $this->accessToken->generate($request->phone_number, 'mb_register_verify_otp'),
        ], message: 'otp.sent');
    }

    public function verifyOpt(VerifyOtpRequest $request)
    {
        (new OneTimePassword(
            phone_number: $request->phone_number,
            device_id: $request->device_id,
        ))->verify(
            user: null,
            otp: $request->otp
        );

        $this->accessToken->delete($request->phone_number, 'mb_register_verify_otp');

        return $this->responseSucceed(
            data: [
                'token' => $this->accessToken->generate($request->phone_number, 'mb_register')
            ],
            message: 'otp.verified'
        );
    }

    public function register(PlayerRegisterRequest $request, UserReference $userReference)
    {
        try {
            DB::beginTransaction();
            $reference_id = $userReference->execute(
                UserPrefix::Player->value,
                $request->phone_number
            );
            User::create([
                'name' => random_number_name(),
                'phone_number' => $request->phone_number,
                'password' => bcrypt($request->password),
                'device_id' => $request->device_id,
                'language' => Language::from($request->language)->value,
                'reference_id' => $reference_id,
                'user_agent' => $request->user_agent,
                'noti_token' => $request->noti_token,
                'ip_address' => $request->ip_address,
                'secret_key' => Str::random(32),
                'registered_at' => now(),
                'last_logged_in_at' => now(),
                'payment_account_number' => $request->phone_number,
                'payment_account_name' => $request->name,
                'payment_type_id' => 1,
            ]);

            $user = User::where('phone_number', $request->phone_number)->first();

            PlayerSetting::create([
                'user_id' => $user->id,
                'game_type_id' => 1,
                'sound_status' => 1,
                'vibration_status' => 1,
                'challenge_status' => 1,
                'friend_status' => 1,
            ]);

            DB::commit();

            $response = (new PasswordGrant(user: $user))->execute(request: $request);

            if ($response->failed()) {
                DB::rollBack();
                return $this->responseSomethingWentWrong(message: $response->json()['message']);
            }

            $this->accessToken->delete($request->phone_number, 'mb_register');
            DB::commit();
            $tokens = $response->json();
            if (isset($request->header()['user-agent'])) {

                $cookie = getUserCookie($tokens);
                if (config('app.env') === "production") {
                    unset($tokens['refresh_token']);
                };
                return $this->responseSucceed(data: [
                    'user' => new PlayerResource($user),
                    'token' => $response->json()
                ], cookie: config('app.env') === "production" ? $cookie : null, message: 'Successfully Registered');
            }
            return $this->responseSucceed(data: [
                'user' => new PlayerResource($user),
                'token' => $tokens
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseSomethingWentWrong(message: "Register Failed!");
        }
    }
}
