<?php

namespace App\Actions;

use App\Traits\Auth\ApiResponse;
use Illuminate\Support\Facades\Http;
use App\Services\Crypto\DataKey;

class HandleEndpoint
{
    public function handle(string $server_path, array $request)
    {

        $response = Http::post($server_path, str_starts_with($server_path, 'https://game-socket.com') ? array_merge($request, [
            'client_id' => env('SOCKET_CLIENT_ID', 'SOID00BD48FBUFTHN67G4D'),
            'client_secret' => env('SOCKET_CLIENT_SECRET', 'SOSC44zmcA8nGvEGBFmvLj8FpQlHgIfxIqt0lBbgRLnPwNheWQyMD')
        ]) : $request);

        return response()->json([
            (new DataKey())->encrypt(
                json_decode($response, true),
                auth()->user()->secret_key
            )
        ]);
    }
}
