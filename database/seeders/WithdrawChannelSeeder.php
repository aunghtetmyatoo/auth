<?php

namespace Database\Seeders;

use App\Models\WithdrawChannel;
use Illuminate\Database\Seeder;
use App\Models\ExchangeCurrency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WithdrawChannelSeeder extends Seeder
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
                'name' => 'We Chat',
                'exchange_currency_id' => ExchangeCurrency::whereName('CNY')->pluck('id')->first(),
                'handling_fee' => 100,
                'telegram_channel_id' => '-1001733825869'
            ],
            [
                'name' => 'Alipay',
                'exchange_currency_id' => ExchangeCurrency::whereName('CNY')->pluck('id')->first(),
                'handling_fee' => 100,
                'telegram_channel_id' => '-1001733825869'
            ],
            [
                'name' => 'Bank Card',
                'exchange_currency_id' => ExchangeCurrency::whereName('CNY')->pluck('id')->first(),
                'handling_fee' => 100,
                'telegram_channel_id' => '-1001733825869'
            ],
            [
                'name' => 'KBZ Pay',
                'exchange_currency_id' => ExchangeCurrency::whereName('MMK')->pluck('id')->first(),
                'handling_fee' => 100,
                'telegram_channel_id' => '-1001733825869'
            ],
            [
                'name' => 'Thai Baht',
                'exchange_currency_id' => ExchangeCurrency::whereName('THB')->pluck('id')->first(),
                'handling_fee' => 100,
                'telegram_channel_id' => '-1001733825869'
            ],
            [
                'name' => 'US Dollar',
                'exchange_currency_id' => ExchangeCurrency::whereName('USD')->pluck('id')->first(),
                'handling_fee' => 100,
                'telegram_channel_id' => '-1001733825869'
            ],
        ];

        foreach ($channels as $channel) {
            WithdrawChannel::firstOrCreate($channel);
        }
    }
}
