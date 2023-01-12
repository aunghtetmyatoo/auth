<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class HandleEndpoint
{
    public function handle(string $server_name, string $prefix, string $route_name, array $request)
    {
        $response = Http::post(config("api.server.$server_name.end_point") . config("api.server.$server_name.$prefix.prefix") . config("api.server.$server_name.$prefix.$route_name"), $server_name === "real_time" ? array_merge($request, [
            'client_id' => env('SOCKET_CLIENT_ID', 'SOID00BD48FBUFTHN67G4D'),
            'client_secret' => env('SOCKET_CLIENT_SECRET', 'SOSC44zmcA8nGvEGBFmvLj8FpQlHgIfxIqt0lBbgRLnPwNheWQyMD')
        ]) : $request);

        return json_decode($response, true);
    }
}
