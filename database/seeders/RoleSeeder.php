<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'Super Admin',
            'Admin',
            'Officer',
            'Operation Manager',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role,'guard_name' => 'admin']);
        }

        $super_admin_permissions = [
            'dashboard',
            'withdraw recharge dashbord',
            'admin list',
            'admin create',
            'admin edit',
            'bot list',
            'bot create',
            'bot edit',
            'bot delete',
            'recharge request list',
            'recharge request confirm',
            'recharge request reject',
            'recharge request complete',
            'withdraw request list',
            'withdraw request confirm',
            'withdraw request refund',
            'recharge request rerequest',
            'withdraw request complete',
            'recharge channel list',
            'recharge channel create',
            'recharge channel edit',
            'recharge channel delete',
            'withdraw channel list',
            'withdraw channel create',
            'withdraw channel edit',
            'withdraw channel delete',
            'exchange rate list',
            'exchange rate create',
            'exchange rate edit',
            'exchange rate delete',
            'game type list',
            'game type create',
            'game type edit',
            'game type delete',
            'game category list',
            'game category create',
            'game category edit',
            'game category delete',
            'user list',
            'user edit',
            'role list',
            'role create',
            'role edit',
            'role delete',
            'permission list',
            'permission create',
            'permission edit',
            'permission delete',
            'permission group list',
            'permission group create',
            'permission group edit',
            'permission group delete'
        ];

        $role = Role::where('name', 'Super Admin')->where('guard_name','admin')->first();

        $role->syncPermissions($super_admin_permissions);

        $admin_permissions = [
            'dashboard',
            'withdraw recharge dashbord',
            'bot list',
            'bot create',
            'bot edit',
            'recharge request list',
            'recharge request confirm',
            'recharge request reject',
            'recharge request complete',
            'withdraw request list',
            'withdraw request confirm',
            'withdraw request refund',
            'withdraw request complete',
            'recharge channel list',
            'recharge channel create',
            'recharge channel edit',
            'withdraw channel list',
            'withdraw channel create',
            'withdraw channel edit',
            'exchange rate list',
            'exchange rate create',
            'exchange rate edit',
            'game type list',
            'game type create',
            'game type edit',
        ];

        $role = Role::where('name', 'Admin')->where('guard_name','admin')->first();

        $role->syncPermissions($admin_permissions);

        $officer_permissions = [
            'dashboard',
            'withdraw recharge dashbord',
            'bot list',
            'recharge request list',
            'withdraw request list',
            'recharge channel list',
            'withdraw channel list',
            'exchange rate list',
            'game type list',
        ];

        $role = Role::where('name', 'Officer')->where('guard_name','admin')->first();

        $role->syncPermissions($officer_permissions);

    }
}
