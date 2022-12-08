<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class HandleEndpoint
{
    public function handle(string $server_name, string $prefix, string $route_name, array $request): array
    {
        $response = Http::post(config("api.server.$server_name.end_point") . config("api.server.$server_name.$prefix.prefix") . config("api.server.$server_name.$prefix.$route_name"), $request);

        return json_decode($response, true);
    }
}
