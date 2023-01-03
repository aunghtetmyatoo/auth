<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserPrefix;
use Illuminate\Support\Str;
use App\Actions\UserReference;
use App\Models\GameType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'User-A',
                'phone_number' => '09791113937',
            ],
            [
                'name' => 'User-B',
                'phone_number' => '09684416973',
            ],
            [
                'name' => 'User-C',
                'phone_number' => '09787656373',
            ],
            [
                'name' => 'User-D',
                'phone_number' => '09442678675',
            ],
            [
                'name' => 'User-E',
                'phone_number' => '09967856425',
            ],
        ];

        foreach ($users as $user) {
            $reference_id = (new UserReference())->execute(UserPrefix::Player->value, $user['phone_number']);

            $user = User::create([
                'name' => $user['name'],
                'phone_number' => $user['phone_number'],
                'password' => bcrypt('password'),
                'reference_id' => $reference_id,
                'device_id' => Str::uuid(),
                'amount' => 900000,
                'registered_at' => now(),
                'payment_account_number' => $user['phone_number'],
                'payment_account_name' => $user['name'],
                'payment_type_id' => 1,
            ]);

            $game_type_id = GameType::where('name', 'ShanKoeMee')->pluck('id')->first();

            $user->game_types()->attach($game_type_id, [
                'coin' => 900000,
            ]);
        }
    }
}
