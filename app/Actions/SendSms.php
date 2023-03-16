<?php

namespace App\Actions;

use App\Exceptions\SmsFailedExcetion;
use Illuminate\Support\Facades\Http;

class SendSms
{
    public function execute(string $phone_number, string $text)
    {
        // $phone_number = str_replace('09', '+959', $phone_number);
        $response = Http::withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . config('sms.token')
        ])->withOptions([
            "verify" => config('app.env') === 'production' ?? false
        ])->post(config('sms.end_point'), [
            'from' => 'BPSMS MM',
            'message' => $text,
            'to'   => $phone_number
        ]);

        info($response);
        // if ($response['status'] != '0') {
        //     throw new SmsFailedExcetion();
        // }
    }
}
