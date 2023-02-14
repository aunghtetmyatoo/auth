<?php

namespace App\Services\Crypto;

use App\Helpers\AuthHelper;
use Illuminate\Support\Facades\Storage;

class RequestKey
{

    public function encrypt(mixed $data): mixed
    {

        $encrypter = new \Illuminate\Encryption\Encrypter($this->getKey(), config('app.cipher'));
        if (is_array($data)) {
            return $encrypter->encryptString(json_encode($data));
        }
        return $encrypter->encryptString($data);
    }

    public function decrypt(string $data): mixed
    {
        $decrypter = new \Illuminate\Encryption\Encrypter($this->getKey(), config('app.cipher'));
        $decrypted = $decrypter->decryptString($data);

        if ($this->isJson($decrypted)) {
            return json_decode($decrypted, true);
        }
        return $decrypted;
    }

    private function getKey()
    {
        $secret_key=auth()->user()->secret_key;
        return $secret_key;

    }

    private function isJson($str)
    {
        $json = json_decode($str);
        return $json && $str != $json;
    }
}
