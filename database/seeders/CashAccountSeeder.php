<?php

namespace Database\Seeders;

use App\Constants\Status;
use App\Models\CashAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cash_accounts = [
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
            [
                'account_name' => 'user',
                'reference_id' => Status::USER_ACC,
                'amount' => 18000000,
            ],
            [
                'account_name' => 'bank',
                'reference_id' => Status::BANK_ACC,
                'amount' => 0,
            ],
            [
                'account_name' => 'payable coin',
                'reference_id' => Status::PAYABLE_COIN,
                'amount' => 0,
            ],
            [
                'account_name' => 'admin',
                'reference_id' => Status::ADMIN_ACC,
                'amount' => 3600000,
            ],
        ];

        foreach ($cash_accounts as $cash_account) {
            $exist = CashAccount::where('reference_id', $cash_account['reference_id'])->first();

            if (!$exist) {
                CashAccount::create([
                    'account_name' => $cash_account['account_name'],
                    'reference_id' => $cash_account['reference_id'],
                    'amount' => $cash_account['amount'],
                ]);
            }
        }
    }
}
