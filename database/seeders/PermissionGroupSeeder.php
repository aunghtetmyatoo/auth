<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            'GroupOne',
            'GroupTwo',
            'GroupThree',
            'Cash Management',
        ];

        foreach ($groups as $group) {
            $existed = PermissionGroup::where('name', $group)->first();

            if (!$existed) {
                PermissionGroup::create([
                    'name' => $group,
                ]);
            }
        }

        PermissionGroup::whereNotIn('name', $groups)->delete();
    }
}
