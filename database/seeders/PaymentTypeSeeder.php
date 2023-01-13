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
            [
                'name' => 'KPAY',
                'account_name' => 'Wanna Min Paing',
                'account_number' => '09799703132',
                'qr_code' => 'Image/PAYMENT/09799703132/QR_CODE09799703132.png',
                'account_photo' => 'Image/PAYMENT/09799703132/ACCOUNT_PHOTO09799703132.png'
            ],
            [
                'name' => 'CBPAY',
                'account_name' => 'Aung Htet Myat Oo',
                'account_number' => '09450887956',
                'qr_code' => 'Image/PAYMENT/09450887956/QR_CODE09450887956.png',
                'account_photo' => 'Image/PAYMENT/09450887956/ACCOUNT_PHOTO09799703132.png'
            ],
            [
                'name' => 'AYAPAY',
                'account_name' => 'Shin Wanna Aung',
                'account_number' => '09444665434',
                'qr_code' => 'Image/PAYMENT/09444665434/QR_CODE09444665434.png',
                'account_photo' => 'Image/PAYMENT/ACCOUNT_PHOTO/ACCOUNT_PHOTO09799703132.png'
            ],
            [
                'name' => 'ONEPAY',
                'account_name' => 'Aye Myat Myat Mon',
                'account_number' => '09799452345',
                'qr_code' => 'Image/PAYMENT/09799452345/QR_CODE09799452345.png',
                'account_photo' => 'Image/PAYMENT/ACCOUNT_PHOTO/ACCOUNT_PHOTO09799703132.png'
            ],
        ];

        foreach ($payment_types as $payment_type) {
            $existed = PaymentType::where('account_number', $payment_type['account_number'])->first();

            if (!$existed) {
                PaymentType::create([   'name' => $payment_type['name'],
                                        'account_name' => $payment_type['account_name'],
                                        'account_number' => $payment_type['account_number'],
                                        'qr_code' => $payment_type['qr_code'],
                                        'account_photo' => $payment_type['account_photo'] ]);
            }
        }

        // PaymentType::whereNotIn('name', $payment_types)->forceDelete();
    }
}
