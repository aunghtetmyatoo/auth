<?php

namespace Database\Seeders;

use App\Models\GeneralLedger;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GeneralLedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $general_ledgers = [
            [
                'name' => 'capital account',
                'reference_id' => 'CAPITAL_ACC',
                'amount' => 900000,
            ],
            [
                'name' => 'house edge',
                'reference_id' => 'HOUSE_EDGE',
                'amount' => 900000,
            ],
            [
                'name' => 'cash in',
                'reference_id' => 'CASH_IN',
                'amount' => 900000,
            ],
            [
                'name' => 'cash out',
                'reference_id' => 'CASH_OUT',
                'amount' => 900000,
            ],
            [
                'name' => 'withdraw payable',
                'reference_id' => 'WDL_PAYABLE',
                'amount' => 900000,
            ],
            [
                'name' => 'withdraw income',
                'reference_id' => 'WDL_INCOME',
                'amount' => 900000,
            ],

        ];

        foreach ($general_ledgers as $general_ledger) {
            $exist = GeneralLedger::whereReferenceId($general_ledger['reference_id'])->first();

            if (!$exist) {
                GeneralLedger::create([
                    'name' => $general_ledger['name'],
                    'reference_id' => $general_ledger['reference_id'],
                    'amount' => $general_ledger['amount'],
                ]);
            }
        }
    }
}
