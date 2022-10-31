<?php

namespace App\Services\Auth;

use App\Models\AccessToken as AccessTokenModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccessToken
{
    public function generate(string $phone_number, string $action): string
    {
        $expired_at = now()->addMinutes(config('auth.access_token.expires.' . $action));

        $plain_text_token = Str::random(rand(config('auth.access_token.min'), config('auth.access_token.max')));

        $this->delete(phone_number: $phone_number, action: $action);

        AccessTokenModel::create([
            'identifier' => $phone_number,
            'token' => Hash::make($plain_text_token),
            'expired_at' => $expired_at,
            'action' => $action
        ]);
        return $plain_text_token;
    }

    public function delete(string $phone_number, string $action): void
    {
        $existed_tokens = AccessTokenModel::whereIdentifier($phone_number)->whereAction($action)->get();
        if (count($existed_tokens)) {
            $existed_tokens->each->delete();
        }
    }
}
