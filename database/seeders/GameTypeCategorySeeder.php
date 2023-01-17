<?php

namespace Database\Seeders;

use App\Constants\GameCategoryConstant;
use App\Models\GameCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameTypeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $game_categories = [
            GameCategoryConstant::CardGame,
            GameCategoryConstant::SlotGame,
        ];

        foreach ($game_categories as $game_category) {
            $existed = GameCategory::where('name', $game_category)->first();

            if (!$existed) {
                GameCategory::create(['name' => $game_category]);
            }
        }

        GameCategory::whereNotIn('name', $game_categories)->forceDelete();
    }
}
