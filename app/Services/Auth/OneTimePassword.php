<?php

namespace App\Services\Auth;

use App\Actions\Auth\InvalidOtp;
use App\Actions\Auth\ResetOtpMistake;
use App\Constants\AuthConstant;
use App\Enums\OtpAction;
use App\Events\OtpRequested;
use App\Exceptions\OtpBlockedException;
use App\Exceptions\OtpExpiredException;
use App\Exceptions\OtpLimitReachedException;
use App\Models\Admin;
use App\Models\Otp;
use App\Models\OtpRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use App\Traits\Auth\ApiResponse;

class OneTimePassword
{
    use ApiResponse;
    public function __construct(private string $phone_number, private string $device_id, private bool $is_backend = false)
    {
    }
    /**
     * @param string $life_time
     * @return mixed
     */
    public function generate(string $life_time, OtpAction $action): int
    {
        OtpRequest::create([
            'phone_number' => $this->phone_number,
            'device_id' => $this->device_id,
            'action' => $action->value,
        ]);

        //delete all otps for this phone number
        Otp::where('identifier', $this->phone_number)->delete();

        //generate token
        $token = get_random_digit(6);

        //create otp for a given expired time
        Otp::create([
            'identifier' => $this->phone_number,
            'device_id' => $this->device_id,
            'token' => Hash::make($token),
            'expired_at' => now()->addMinutes($life_time)
        ]);

        return $token;
    }

    public function send(Admin|User $user = null, OtpAction $action, int $life_time): void
    {
        if (!config('app.otp')) {
            return;
        }

        $this->checkRequestLimit();
        $this->checkVerifyLimit($user);
        $this->checkBlocked();

        event(new OtpRequested(
            phone_number: $this->phone_number,
            device_id: $this->device_id,
            action: $action,
            is_backend: $this->is_backend,
            life_time: $life_time
        ));
    }

    public function verify(User $user = null, string $otp): void
    {
        if (!config('app.otp')) {
            return;
        }
        $requested_otp = $this->checkRequested();

        $this->checkVerifyLimit($user);
        $this->checkOtpValid(user: $user, requested_otp: $requested_otp, confirm_otp: $otp);
        $this->checkOtpExpire(otp: $requested_otp);
        $this->otpIsValid(user: $user, otp: $requested_otp);
    }

    // check phone number exceeded the limit to request otp
    private function checkRequestLimit()
    {
        // if (in_array($this->phone_number, AuthConstant::DEV_PHONE_NUMBERS)) {
        //     return;
        // }

        $otp_requests = OtpRequest::where('phone_number', $this->phone_number)->get();

        if (count($otp_requests) >= config('auth.index.allow.requests.otp')) {
            throw new OtpLimitReachedException(message: 'otp.over_limit.request');
        }
    }

    // check phone number exceeded the limit to request otp
    private function checkVerifyLimit(Admin|User $user = null)
    {
        if (!$user) {
            return;
        }

        $mistook_col = $this->is_backend ? 'bk_otp_mistook_at' : 'otp_mistook_at';
        $mistake_col = $this->is_backend ? 'bk_otp_mistake_count' : 'otp_mistake_count';

        (new ResetOtpMistake())->handle(user: $user, mistake_col: $mistake_col, mistook_col: $mistook_col);

        if ($user->$mistake_col >= config('auth.index.allow.mistake.otp')) {
            throw new OtpLimitReachedException(message: 'otp.over_limit.verify');
        }
    }

    // check user blocked to request an otp 
    private function checkBlocked()
    {
        // if an admin block this device id
        $otp_blocked = OtpRequest::where('device_id', $this->device_id)->whereIsBlocked(true)->first();

        if ($otp_blocked) {
            throw new OtpBlockedException();
        }
    }

    // check user requested an otp
    private function checkRequested()
    {
        $requested = Otp::where('identifier', $this->phone_number)->where('device_id', $this->device_id)->first();
        if (!$requested) {
            throw new AuthenticationException();
        }

        return $requested;
    }

    private function checkOtpValid(Admin|User $user = null, Otp $requested_otp, string $confirm_otp)
    {
        if (!Hash::check($confirm_otp, $requested_otp->token)) {
            (new InvalidOtp())->handle(user: $user, is_backend_user: $this->is_backend);
        }
    }

    private function checkOtpExpire(Otp $otp)
    {
        if ($otp->expired_at <= now()) {
            throw new OtpExpiredException();
        }
    }

    public function otpIsValid(Admin|User $user = null, Otp $otp)
    {
        if ($user) {
            $mistake_col = $this->is_backend ? 'bk_otp_mistake_count' : 'otp_mistake_count';
            $user->$mistake_col = 0;
            $user->save();
        }
        $otp->delete();
    }
}
