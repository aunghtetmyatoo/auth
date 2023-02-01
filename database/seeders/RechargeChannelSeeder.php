<?php

namespace Database\Seeders;

use App\Models\RechargeChannel;
use Illuminate\Database\Seeder;
use App\Models\ExchangeCurrency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RechargeChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $channels = [
            [
                'name' => 'KBZ Pay',
                'currency_id' => ExchangeCurrency::whereName('MMK')->pluck('id')->first(),
                'telegram_channel_id' => '-1001733825869'
            ],
            [
                'name' => 'USDT',
                'currency_id' => ExchangeCurrency::whereName('USDT')->pluck('id')->first(),
                'telegram_channel_id' => '-1001733825869'
            ],
        ];

        foreach ($channels as $channel) {
            RechargeChannel::firstOrCreate($channel);
        }
    }
}
