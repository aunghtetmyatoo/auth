<?php

namespace Database\Seeders;

use App\Models\GameType;
use App\Models\GameCategory;
use App\Constants\GameCategory as GameCategoryConstant;
use App\Constants\Gametype as GametypeConstant;
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
            GametypeConstant::SKM,
            GametypeConstant::THP,
            GametypeConstant::BG,
            GametypeConstant::TTO,
        ];

        $game_category_id = GameCategory::where('name', GameCategoryConstant::CARD)->pluck('id')->first();

        foreach ($games_types as $type) {
            $existed = GameType::where('name', $type)->first();

            if (!$existed) {
                GameType::create(['name' => $type, 'game_category_id' => $game_category_id]);
            }
        }

        GameType::whereNotIn('name', $games_types)->forceDelete();
    }
}
