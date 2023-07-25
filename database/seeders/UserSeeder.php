<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GameType;
use Illuminate\Support\Str;
use App\Models\PlayerSetting;
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
                'phone_number' => '+959791113937',
            ],
            [
                'name' => 'User-B',
                'phone_number' => '+959684416973',
            ],
            [
                'name' => 'User-C',
                'phone_number' => '+959787656373',
            ],
            [
                'name' => 'User-D',
                'phone_number' => '+959442678675',
            ],
            [
                'name' => 'User-E',
                'phone_number' => '+959967856425',
            ],
            [
                'name' => 'User-F',
                'phone_number' => '+959453456783',
            ],
            [
                'name' => 'User-G',
                'phone_number' => '+959888267281',
            ],
            [
                'name' => 'User-H',
                'phone_number' => '+959717416152',
            ],
            [
                'name' => 'User-I',
                'phone_number' => '+959963382922',
            ],
            [
                'name' => 'User-J',
                'phone_number' => '+959257668976',
            ],
            [
                'name' => 'User-K',
                'phone_number' => '+959883537611',
            ],
            [
                'name' => 'User-L',
                'phone_number' => '+959777777771',
            ],
            [
                'name' => 'User-M',
                'phone_number' => '+959777777772',
            ],
            [
                'name' => 'User-N',
                'phone_number' => '+959777777773',
            ],
            [
                'name' => 'User-O',
                'phone_number' => '+959777777774',
            ],
            [
                'name' => 'User-P',
                'phone_number' => '+959777777775',
            ],
            [
                'name' => 'User-Q',
                'phone_number' => '+959777777776',
            ],
            [
                'name' => 'User-R',
                'phone_number' => '+959777777777',
            ],
            [
                'name' => 'User-S',
                'phone_number' => '+959777777778',
            ],
            [
                'name' => 'User-T',
                'phone_number' => '+959777777779',
            ],
        ];

        foreach ($users as $user) {
            $existed = User::where('phone_number', $user['phone_number'])->first();

            if (!$existed) {
                $reference_id = random_string() . random_number_name();

                $user = User::create([
                    'name' => random_number_name(),
                    'phone_number' => $user['phone_number'],
                    'password' => bcrypt('password'),
                    'reference_id' => $reference_id,
                    'device_id' => Str::uuid(),
                    'amount' => 900000,
                    'registered_at' => now(),
                    'payment_account_number' => $user['phone_number'],
                    'payment_account_name' => $user['name'],
                    'payment_type_id' => 1,
                    'secret_key' => Str::random(32),
                ]);

                $game_type_id = GameType::where('name', 'ShanKoeMee')->pluck('id')->first();

                $user->game_types()->attach($game_type_id, [
                    'coin' => 900000,
                ]);

                PlayerSetting::create([
                    'user_id' => $user->id,
                    'game_type_id' => $game_type_id,
                    'sound_status' => 1,
                    'vibration_status' => 1,
                    'challenge_status' => 1,
                    'friend_status' => 1,
                ]);
            }
        }
    }
}
