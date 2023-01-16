<?php

namespace Database\Seeders;

use App\Models\Bot;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bots = [
            [
                'name' => 'Bot-A',
            ],
            [
                'name' => 'Bot-B',
            ],
            [
                'name' => 'Bot-C',
            ],
            [
                'name' => 'Bot-D',
            ],
            [
                'name' => 'Bot-E',
            ],
        ];

        foreach ($bots as $bot) {
            $bot = Bot::create([
                'name' => $bot['name'],
                'amount' => 900000,
                'coin' => 900000,
            ]);
        }
    }
}
