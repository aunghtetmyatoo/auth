<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = [
            [
                'provider' => 'players',
                'name' => 'Player Password Grant Client'
            ],
        ];

        foreach ($clients as $client) {
            $exist = DB::table('oauth_clients')->where('name', $client['name'])->first();

            if (!$exist) {
                Artisan::call('passport:client', [
                    '--password' => null,
                    '--provider' => $client['provider'],
                    '--name' => $client['name'],
                    '-n' => null,
                ]);
            }
        }

        DB::table('oauth_clients')->whereNotIn('name', array_column($clients, 'name'))->delete();


        $exist = DB::table('oauth_clients')->whereName('Personal Access Token')->first();

        if (!$exist) {
            Artisan::call('passport:client', [
                '--personal' => null,
                '--name' => 'Personal Access Token',
                '-n' => null,
            ]);
        }
    }
}
