<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupPermissions = [
            'GroupOne' => [
                'dashboard',
                'withdraw recharge dashbord',
                'admin list',
                'admin create',
                'admin edit',
                'admin qr generate',
                'admin qr',
                'user list',
                'user edit',
                'user delete',
                'bot list',
                'bot create',
                'bot edit',
                'bot delete',
                'exchange rate list',
                'exchange rate create',
                'exchange rate edit',
                'exchange rate delete',
                'game category list',
                'game category create',
                'game category edit',
                'game category delete',
                'game type list',
                'game type create',
                'game type edit',
                'game type delete',
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
            ],
            'GroupTwo' =>[
                'recharge request list',
                'recharge request confirm',
                'recharge request reject',
                'recharge request rerequest',
                'recharge request complete',
                'recharge channel list',
                'recharge channel create',
                'recharge channel edit',
                'recharge channel delete',
            ],
            'GroupThree' => [
                'withdraw request list',
                'withdraw request confirm',
                'withdraw request refund',
                'withdraw request refunding',
                'withdraw request rerequest',
                'withdraw request complete',
                'withdraw channel list',
                'withdraw channel create',
                'withdraw channel edit',
                'withdraw channel delete',
            ],
            'Cash Management' => [
                'refill to it head analysis',
                'refill to super admin analysis',
                'refill to finance manager analysis',
                'refill to finance analysis',
                'refill to operation manager analysis',
                'refill to player analysis',
                'return to super admin analysis',
                'return to finance manager analysis',
                'return to finance analysis'
            ],
        ];

        foreach ($groupPermissions as $group_name => $permissions)
        {
            $group = PermissionGroup::where('name', $group_name)->first();

            foreach ($permissions as $permission) {
                $existed = Permission::where('name', $permission)->first();

                if (!$existed) {
                    $permission = $group->permissions()->create([
                        'name' => $permission,
                    ]);
                }
            }
        }
    }
}
