<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;
use App\Actions\PaymentTypeReference;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_types = [
            'KPAY', 'CBPAY', 'AYAPAY', 'ONEPAY',
        ];

        foreach ($payment_types as $type) {
            $existed = PaymentType::where('name', $type)->first();

            if (!$existed) {
                $generate_payment_type_ref_id = new PaymentTypeReference();
                PaymentType::create(['name' => $type, 'reference_id' => $generate_payment_type_ref_id->execute($type)]);
            }
        }

        PaymentType::whereNotIn('name', $payment_types)->forceDelete();
    }
}
