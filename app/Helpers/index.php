<?php

/**
 * merge array with required
 */

use Illuminate\Http\Request;
use PhpParser\Node\NullableType;

use function PHPSTORM_META\type;

if (!function_exists('get_enum_values')) {
    function get_enum_values(array $enums): array
    {
        $array = [];
        foreach ($enums as $enum) {
            array_push($array, $enum->value);
        }
        return $array;
    }
}

/**
 * 
 * get delicated string from enum
 */
if (!function_exists('get_enum_string')) {
    function get_enum_string(array $array, $separator): string
    {
        return implode($separator, get_enum_values($array));
    }
}

/**
 * 
 * get random digits
 */
if (!function_exists('get_random_digit')) {
    function get_random_digit(int $length): string
    {
        $i = 0;
        $pin = "";

        while ($i < $length) {
            $pin .= mt_rand(0, 9);
            $i++;
        }

        return $pin;
    }
}

/**
 * 
 * get delicated string from salt
 */
if (!function_exists('get_random_str')) {
    function get_random_str(bool $upper_cases = true, bool $numbers = true, bool $special_charaters = false): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';

        if ($upper_cases) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        if ($numbers) {
            $characters .= '0123456789';
        }

        if ($special_charaters) {
            $characters .= "~`! @#$%^&*()_-+={[}]|'\:;<,>.?/";
        }

        $randomstr = '';
        for ($i = 0; $i < 8; $i++) {
            $randomstr .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomstr;
    }
}

if (!function_exists('getUserCookie')) {
    function getUserCookie(array $refresh_token = [])
    {
        $cookie = cookie(
            name: 'refresh_token',
            value: $refresh_token['refresh_token'],
            minutes: config('session.lifetime'),
            path: '/',
            domain: '.localhost',
            secure: env('app.env') === 'production',
            httpOnly: true,
            raw: false,
            sameSite: 'None'
        );
        return $cookie;
    }
}

if (!function_exists('checkUserStatus')) {
    function checkUserStatus(object|NULL $user, string $device_id = "", string $status = "")
    {
        $type = gettype($user);
        if ($type === "NULL") {
            return [
                "status" => false,
                "message" => "Something Went Wrong"
            ];
        } elseif ($type === "object") {
            if ($status === "CHECK_DEVICE") {
                if ($device_id == null || $device_id == "") {
                    return [
                        "status" => false,
                        "message" => "Something Went Wrong"
                    ];
                } else {
                    if ($user->device_id != $device_id) {
                        return [
                            "status" => false,
                            "message" => "Something Went Wrong"
                        ];
                    }
                }
            }
            if ($user->frozen_at) {
                return [
                    "status" => false,
                    "message" => "Something Went Wrong"
                ];
            }
            if ($user->password_mistook_at) {
                return [
                    "status" => false,
                    "message" =>  "passwords.freezed"
                ];
            }
        }
        return [
            "status" => true,
            "message" => null
        ];
    }
}
