<?php

namespace App\CustomFunctions;

use App\Models\Pay_user;
use Illuminate\Support\Facades\Crypt;

class ResponseHelpers
{

    public static function customResponse($status_code, $message)
    {
        return response()->json(
            [
                'message' => $message,
                'status'  => $status_code,
            ],
            $status_code
        );
    }
}
