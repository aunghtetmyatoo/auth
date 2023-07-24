<?php

namespace Database\Seeders;

use App\Constants\Status;
use App\Models\EMoneyAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EMoneyAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $e_money_accounts = [
            [
                'account_name' => 'capital',
                'reference_id' => Status::CAPITAL_ACC,
                'amount' => 10000,
            ],
            [
                'account_name' => 'recharge income',
                'reference_id' => Status::RECHARGE_INCOME,
                'amount' => 0,
            ],
            [
                'account_name' => 'withdraw income',
                'reference_id' => Status::WITHDRAW_INCOME,
                'amount' => 0,
            ],
            [
                'account_name' => 'house edge',
                'reference_id' => Status::HOUSE_EDGE,
                'amount' => 0,
            ],
            [
                'account_name' => 'exchange gain',
                'reference_id' => Status::EXCHANGE_GAIN,
                'amount' => 0,
            ],
            [
                'account_name' => 'exchange loss',
                'reference_id' => Status::EXCHANGE_LOSS,
                'amount' => 0,
            ],
        ];

        foreach ($e_money_accounts as $e_money_account) {
            $exist = EMoneyAccount::where('reference_id', $e_money_account['reference_id'])->first();

            if (!$exist) {
                EMoneyAccount::create([
                    'account_name' => $e_money_account['account_name'],
                    'reference_id' => $e_money_account['reference_id'],
                    'amount' => $e_money_account['amount'],
                ]);
            }
        }
    }
}
