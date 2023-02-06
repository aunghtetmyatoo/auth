<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'recharge request list',
            'recharge request create',
            'recharge request edit',
            'recharge request delete',
        ];

        $role = Role::where('name', 'Super Admin')->where('guard_name','admin')->first();

        foreach ($permissions as $permission)
        {
            $permission  = Permission::create(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}
