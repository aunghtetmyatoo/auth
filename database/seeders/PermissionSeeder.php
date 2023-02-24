<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminPermissions = [
            'User' => [
                'recharge request list',
                'recharge request create',
                'recharge request edit',
                'recharge request delete',
            ],
        ];

        $role = Role::where('name', 'Super Admin')->where('guard_name','admin')->first();

        foreach ($adminPermissions as $group_name => $permissions)
        {
            $group = PermissionGroup::where('name', $group_name)->first();

            foreach ($permissions as $permission) {
                $permission = $group->permissions()->create([
                    'name' => $permission,
                ]);

                $role->givePermissionTo($permission);
            }

        }
    }
}
