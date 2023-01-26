<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GameType;
use App\Constants\Status;
use App\Enums\UserPrefix;
use Illuminate\Support\Str;
use App\Actions\UserReference;
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
            [
                'name' => 'User-F',
                'phone_number' => '09453456783',
            ],
            [
                'name' => 'User-G',
                'phone_number' => '09888267281',
            ],
            [
                'name' => 'User-H',
                'phone_number' => '09717416152',
            ],
            [
                'name' => 'User-I',
                'phone_number' => '09963382922',
            ],
            [
                'name' => 'User-J',
                'phone_number' => '09257668976',
            ],
            [
                'name' => 'Bot-A',
                'phone_number' => '09777777771',
            ],
            [
                'name' => 'Bot-B',
                'phone_number' => '09777777772',
            ],
            [
                'name' => 'Bot-C',
                'phone_number' => '09777777773',
            ],
            [
                'name' => 'Bot-D',
                'phone_number' => '09777777774',
            ],
            [
                'name' => 'Bot-E',
                'phone_number' => '09777777775',
            ],
        ];

        foreach ($users as $user) {
            $reference_id = (new UserReference())->execute(UserPrefix::Bot->value, $user['phone_number']);

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
                'role' => str_contains($user['name'], 'User') ? Status::USER : Status::BOT,
            ]);

            $game_type_id = GameType::where('name', 'ShanKoeMee')->pluck('id')->first();

            $user->game_types()->attach($game_type_id, [
                'coin' => 900000,
            ]);
        }
    }
}
