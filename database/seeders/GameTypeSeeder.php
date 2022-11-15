<?php

namespace Database\Seeders;

use App\Models\GameType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class GameTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games_types = [
            'ShanKoeMee', 'BuuGyi', 'Poker', 'TweentyOne',
        ];

        foreach ($games_types as $type) {
            $existed = GameType::where('name', $type)->first();

            if (!$existed) {
                GameType::create(['name' => $type, 'reference_id' => time() . rand(10 * 45, 100 * 98)]);
            }
        }

        GameType::whereNotIn('name', $games_types)->forceDelete();
    }
}
