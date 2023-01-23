<?php

namespace Database\Seeders;

use App\Actions\GenerateReferenceId;
use App\Enums\TransactionType;
use Illuminate\Database\Seeder;
use App\Models\TransactionType as TransactionTypeModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            TransactionType::Gift,
            TransactionType::Cash,
            TransactionType::Bot,
            TransactionType::Player,
        ];

        foreach ($transaction_types as $transaction_type) {
            $existed = TransactionTypeModel::where('name', $transaction_type)->first();

            if (!$existed) {
                TransactionTypeModel::create([
                    'name' => $transaction_type,
                    'reference_id' => (new GenerateReferenceId())->execute(),
                ]);
            }
        }

        TransactionTypeModel::whereNotIn('name', $transaction_types)->forceDelete();
    }
}
