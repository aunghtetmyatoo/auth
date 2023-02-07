<?php

namespace Database\Seeders;

use App\Enums\UserPrefix;
use App\Actions\UserReference;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            [
                'name' => 'Admin-1',
                'phone_number' => '09873673861',
                'role'=>'Operation Manager',
                'amount'=>900000
            ],
            [
                'name' => 'Admin-2',
                'phone_number' => '09837627622',
                'role' => 'IT',
                'amount' => 900000


            ],
            [
                'name' => 'Admin-3',
                'phone_number' => '09967735625',
                'role' => 'Admin',
                'amount' => 900000


            ],
            [
                'name' => 'Admin-4',
                'phone_number' => '09448767198',
                'role' => 'Admin',
                'amount' => 900000


            ],
            [
                'name' => 'Admin-5',
                'phone_number' => '09775637163',
                'role' => 'Admin',
                'amount' => 900000


            ],
        ];

        foreach ($admins as $admin) {
            $reference_id = (new UserReference())->execute(UserPrefix::Admin->value, $admin['phone_number']);

            $admin = Admin::create([
                'name' => $admin['name'],
                'phone_number' => $admin['phone_number'],
                'password' => bcrypt('12345'),
                'reference_id' => $reference_id,
                'registered_at' => now(),
                'mfa_secret' => '12345',
                'role'=>$admin['role'],
                'amount'=>$admin['amount']
            ]);
        }
    }
}
