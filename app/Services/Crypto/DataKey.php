<?php

namespace App\Services\Crypto;

use App\Helpers\AuthHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\EncryptException;

class DataKey
{
    public function __construct(private string $reference_id = "")
    {
    }

    public function encrypt(mixed $data): mixed
    {
        $iv = config('app.cipher_iv');

        $value = \openssl_encrypt(
            $data,
            strtolower(config('app.cipher')),
            $this->getKey(),
            0,
            $iv
        );

        $iv = base64_encode($iv);

        if ($value === false) {
            throw new EncryptException('Could not encrypt the data.');
        }

        // $mac = self::$supportedCiphers[strtolower($this->cipher)]['aead']
        //     ? '' // For AEAD-algoritms, the tag / MAC is returned by openssl_encrypt...
        //     : $this->hash($iv, $value);

        $mac = '';

        $json = json_encode(compact('iv', 'value', 'mac'), JSON_UNESCAPED_SLASHES);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new EncryptException('Could not encrypt the data.');
        }

        $encrypted = base64_encode($json);

        if (is_array($encrypted)) {
            return json_encode($encrypted);
        }
        return $encrypted;
    }

    public function decrypt(string $data): mixed
    {
        $payload = json_decode(base64_decode($data), true);

        $iv = base64_decode($payload['iv']);

        $decrypted = \openssl_decrypt(
            $payload['value'],
            strtolower(config('app.cipher')),
            $this->getKey(),
            0,
            $iv
        );

        if ($this->isJson($decrypted)) {
            return json_decode($decrypted, true);
        }

        return $decrypted;
    }

    private function getKey()
    {
        if (auth()->check()) {
            $secret_key = auth()->user()->secret_key;
            return $secret_key;
        }
    }

    private function isJson($str)
    {
        $json = json_decode($str);
        return $json && $str != $json;
    }

    public function validate($request, $validate_key_array)
    {
        $decrypt_data = (new DataKey())->decrypt($request->encrypt);
        foreach ($validate_key_array as $validate_key) {
            if ($decrypt_data[$validate_key] != $request->{$validate_key}) {
                return [
                    'result' => 0,
                    'message' => 'The given data is invalid.'
                ];
            }
        }

        return [
            'result' => 1,
            'message' => 'success'
        ];
    }
}
