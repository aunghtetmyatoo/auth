<?php

namespace Database\Seeders;

use App\Constants\GameCategoryConstant;
use App\Models\GameCategory;
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

        $game_category_id = GameCategory::where('name',GameCategoryConstant::CardGame)->pluck('id')->first();

        foreach ($games_types as $type) {
            $existed = GameType::where('name', $type)->first();

            if (!$existed) {
                GameType::create(['name' => $type,'game_category_id' => $game_category_id ]);
            }
        }

        GameType::whereNotIn('name', $games_types)->forceDelete();
    }
}
