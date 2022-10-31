<?php

/**
 * merge array with required
 */
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
