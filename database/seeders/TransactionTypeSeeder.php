<?php

namespace Database\Seeders;

use App\Actions\TransactionTypeReference;
use App\Constants\TransactionTypeConstant;
use App\Models\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transaction_types = [
            TransactionTypeConstant::Gift_Transaction,
            TransactionTypeConstant::Cash_Transaction,
            TransactionTypeConstant::Bot_Transaction,
        ];

        foreach ($transaction_types as $transaction_type) {
            $existed = TransactionType::where('name', $transaction_type)->first();

            if (!$existed) {
                $transaction_type_reference_id = new TransactionTypeReference();
                TransactionType::create(['name' => $transaction_type, 'reference_id' => $transaction_type_reference_id->execute($transaction_type)]);
            }
        }

        TransactionType::whereNotIn('name', $transaction_types)->forceDelete();

    }
}
