<?php

namespace Database\Seeders;

use App\Enums\UserPrefix;
use App\Actions\UserReference;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

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
                'phone_number' => '+959873673861',
                'role' => 'IT',
                'amount' => 0
            ],
            [
                'name' => 'Admin-2',
                'phone_number' => '+959837627622',
                'role' => 'IT Head',
                'amount' => 0
            ],
            [
                'name' => 'Admin-3',
                'phone_number' => '+959967735625',
                'role' => 'Super Admin',
                'amount' => 900000
            ],
            [
                'name' => 'Admin-4',
                'phone_number' => '+959448767198',
                'role' => 'Finance Manager',
                'amount' => 900000
            ],
            [
                'name' => 'Admin-5',
                'phone_number' => '+959775637163',
                'role' => 'Finance',
                'amount' => 900000
            ],
            [
                'name' => 'Admin-6',
                'phone_number' => '+959453898724',
                'role' => 'Operation Manager',
                'amount' => 900000
            ],
            [
                'name' => 'Admin-7',
                'phone_number' => '+959453345234',
                'role' => 'Exchange Head',
                'amount' => 0
            ],
            [
                'name' => 'Admin-8',
                'phone_number' => '+959453323563',
                'role' => 'Exchange Operation',
                'amount' => 0
            ],
            [
                'name' => 'Admin-9',
                'phone_number' => '+959453356754',
                'role' => 'Exchange Admin',
                'amount' => 0
            ],
        ];

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($admins as $admin) {
            $existed = Admin::wherePhoneNumber($admin['phone_number'])->first();

            if (!$existed) {
                $reference_id = (new UserReference())->execute(UserPrefix::Admin->value, $admin['phone_number']);

                $admin = Admin::create([
                    'name' => $admin['name'],
                    'phone_number' => $admin['phone_number'],
                    'password' => bcrypt('12345'),
                    'reference_id' => $reference_id,
                    'registered_at' => now(),
                    'mfa_secret' => '12345',
                    'role' => $admin['role'],
                    'amount' => $admin['amount']
                ]);
            }

            switch ($admin['role']) {
                case 'IT':
                    $admin->assignRole('IT');
                    break;
                case 'IT Head':
                    $admin->assignRole('IT Head');
                    break;
                case 'Super Admin':
                    $admin->assignRole('Super Admin');
                    break;
                case 'Finance Manager':
                    $admin->assignRole('Finance Manager');
                    break;
                case 'Finance':
                    $admin->assignRole('Finance');
                    break;
                case 'Operation Manager':
                    $admin->assignRole('Operation Manager');
                    break;
                case 'Exchange Head':
                    $admin->assignRole('Exchange Head');
                    break;
                case 'Exchange Operation':
                    $admin->assignRole('Exchange Operation');
                    break;
                case 'Exchange Admin':
                    $admin->assignRole('Exchange Admin');
                    break;
            }
        }
    }
}
