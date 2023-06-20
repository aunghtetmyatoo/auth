<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExchangeCurrency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExchangeCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            [
                'name' => 'MMK',
                'sign' => 'MMK',
                'buy_rate' => 1,
                'sell_rate' => 1,
            ],
            [
                'name' => 'CNY',
                'sign' => '¥',
                'buy_rate' => 1,
                'sell_rate' => 1,
            ],
            [
                'name' => 'THB',
                'sign' => '฿',
                'buy_rate' => 4.95,
                'sell_rate' => 4.85,
            ],
            [
                'name' => 'USD',
                'sign' => '$',
                'buy_rate' => 0.14,
                'sell_rate' => 0.12,
            ],
            [
                'name' => 'USDT',
                'sign' => 'USDT',
                'buy_rate' => 0.14,
                'sell_rate' => 0.12,
            ]
        ];

        foreach($currencies as $currency){
            $exist = ExchangeCurrency::where('name', $currency['name'])->first();

            if(!$exist){
                ExchangeCurrency::create($currency);
            }
        }
    }
}
