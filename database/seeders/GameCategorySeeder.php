<?php

namespace Database\Seeders;

use App\Models\GameCategory;
use App\Constants\GameCategory as GameCategoryConstant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $game_categories = [
            GameCategoryConstant::CARD,
            GameCategoryConstant::SLOT,
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
