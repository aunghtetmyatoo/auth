<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use App\Services\Crypto\DataKey;

class Endpoint
{
    public function handle(string $domain, string $path, array $request = [])
    {
        $response = Http::post($domain . $path, str_starts_with($domain, 'https://game-socket.com') ? array_merge($request, [
            'client_id' => env('SOCKET_CLIENT_ID', 'SOID00BD48FBUFTHN67G4D'),
            'client_secret' => env('SOCKET_CLIENT_SECRET', 'SOSC44zmcA8nGvEGBFmvLj8FpQlHgIfxIqt0lBbgRLnPwNheWQyMD')
        ]) : $request);

        if (!config('app.crypto')) {
            return json_decode($response);
        }

        return response()->json((new DataKey())->encrypt($response));
    }
}
