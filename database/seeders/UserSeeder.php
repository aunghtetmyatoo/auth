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
                'name' => 'Sa Nay Nay Oo',
                'phone_number' => '09791113937',
            ],
            [
                'name' => 'Naung Ye Htet',
                'phone_number' => '09684416973',
            ],
            [
                'name' => 'Aung Aung',
                'phone_number' => '09787656373',
            ],
            [
                'name' => 'Htet Htet',
                'phone_number' => '09442678675',
            ],
            [
                'name' => 'Myat Myat',
                'phone_number' => '09967856425',
            ],
        ];

        foreach ($users as $user) {
            $reference_id = (new UserReference())->execute(UserPrefix::Player->value, $user['phone_number']);

            $user = User::create([
                'name' => $user['name'],
                'phone_number' => $user['phone_number'],
                'password' => bcrypt('12345'),
                'reference_id' => $reference_id,
                'device_id' => Str::uuid(),
                'amount' => 10000,
                'coins' => 100,
                'registered_at' => now(),
                'payment_account_number' => $user['phone_number'],
                'payment_account_name' => $user['name'],
                'payment_type_id' => 1,
            ]);

            $game_type = GameType::where('name', 'ShanKoeMee')->first();

            $user->game_types()->attach($game_type);
        }
    }
}
