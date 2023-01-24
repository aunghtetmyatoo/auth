<?php

namespace App\Http\Controllers;

use App\Constants\TelegramConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{

    public function sendMessage(Request $request)
    {
        Http::post('https://api.telegram.org/bot'.TelegramConstant::bot_token.'/sendMessage', [
            'chat_id' =>TelegramConstant::chat_id,
            'text' =>   'Account Name = '.$request->account_name.PHP_EOL.
                        'Account Number = '.$request->account_number.PHP_EOL.
                        'Amount ='.$request->amount,
        ]);
    }

    public function sendPhoto(Request $request)
    {

        return $request->photo;

        return Http::post('https://api.telegram.org/bot'.TelegramConstant::bot_token.'/sendPhoto', [
            'chat_id' =>TelegramConstant::chat_id,
            'photo' => $request->photo,
            'caption' =>'Account Name = '.$request->account_name.PHP_EOL.
                        'Account Number = '.$request->account_number.PHP_EOL.
                        'Amount ='.$request->amount,
        ]);
    }
}
