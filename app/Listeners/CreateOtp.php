<?php

namespace App\Listeners;

use App\Events\OtpRequested;
use App\Services\Auth\OneTimePassword;
use App\Actions\SendSms;

class CreateOtp
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OtpRequested  $event
     * @return void
     */
    public function handle(OtpRequested $event)
    {
        $otp = (new OneTimePassword(
            phone_number: $event->phone_number,
            device_id: $event->device_id,
            is_backend: $event->is_backend
        ))->generate($event->life_time, $event->action);

        info('Generated Otp >>>>', [strval($otp)]);

        // (new SendSms())->execute(phone_number: $event->phone_number, text: $otp . trans('otp.message'));
    }
}
