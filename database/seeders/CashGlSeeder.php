<?php

namespace Database\Seeders;

use App\Constants\CashGlConstant;
use App\Models\CashGl;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashGlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cashGles = [
            [
                'name' => CashGlConstant::Application_Cash,
                'amount' => 23400000,
                'status' => true,
            ],
            [
                'name' => CashGlConstant::Bank,
                'amount' => 0,
                'status' => true,
            ],
        ];


        foreach ($cashGles as $cashGl) {
            $existed = CashGl::where('name', $cashGl["name"])->first();

            if (!$existed) {
                CashGl::create([
                    'name' => $cashGl["name"],
                    'amount' => $cashGl["amount"],
                    'status' => $cashGl["status"],
                ]);
            }
        }
    }
}
