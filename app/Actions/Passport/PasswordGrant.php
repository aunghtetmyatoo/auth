<?php

namespace App\Actions\Passport;

use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client as PassportClient;

class PasswordGrant
{
    public function __construct(private User $user)
    {
    }

    public function execute(Request $request): Response
    {
        $this->revoke();
        $client = $this->getClientByUser();
        if (!$client) {
            abort(500);
        }
        $response = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request->phone_number,
            'password' => $request->password,
            'scope' => '',
        ]);
        return $response;
    }


    public function refreshToken(string $refresh_token): Response
    {
        $this->revoke();
        $client = $this->getClientByUser();

        if (!$client) {
            abort(500);
        }
        $response = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
        ]);

        return $response;
    }


    private function revoke()
    {
        if ($this->user->tokens->count() > 0) {
            $this->user->tokens->each(function ($auth_token, $key) {
                $auth_token->revoke();
            });
        }
    }

    private function getClientByUser()
    {
        if ($this->user instanceof User) {
            return PassportClient::whereName('Player Password Grant Client')->first();
        }
    }
}
