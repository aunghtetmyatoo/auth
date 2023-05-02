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
            'IT',
            'IT Head',
            'Super Admin',
            'Finance Manager',
            'Finance',
            'Operation Manager',
        ];

        foreach ($roles as $role) {
            $existed = Role::where('name', $role)->first();

            if (!$existed) {
                Role::firstOrCreate(['name' => $role, 'guard_name' => 'admin']);
            }
        }

        $permissions = [
            'dashboard',
            'withdraw recharge dashbord',
            'admin list',
            'admin create',
            'admin edit',
            'admin qr generate',
            'admin qr',
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

        $it_permissions = [
            'refill to it head analysis',
        ];
        $it_head_permissions = [
            'refill to super admin analysis',
        ];
        $super_admin_permissions = [
            'refill to finance manager analysis',
        ];
        $finance_manager_permissions = [
            'refill to finance analysis',
            'return to super admin analysis',
        ];
        $finance_permissions = [
            'refill to operation manager analysis',
            'return to finance manager analysis'
        ];

        foreach ($permissions as $permission)
        {
            array_push($it_permissions, $permission);
            array_push($it_head_permissions, $permission);
            array_push($super_admin_permissions, $permission);
            array_push($finance_manager_permissions, $permission);
            array_push($finance_permissions, $permission);
        }

        $roles = Role::where('guard_name', 'admin')->get();

        foreach ($roles as $role)
        {
            switch ($role->name) {
                case 'IT':
                    $role->syncPermissions($it_permissions);
                    break;
                case 'IT Head':
                    $role->syncPermissions($it_head_permissions);
                    break;
                case 'Super Admin':
                    $role->syncPermissions($super_admin_permissions);
                    break;
                case 'Finance Manager':
                    $role->syncPermissions($finance_manager_permissions);
                    break;
                case 'Finance':
                    $role->syncPermissions($finance_permissions);
                    break;
            }
        }
    }
}
