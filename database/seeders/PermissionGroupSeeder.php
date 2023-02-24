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
            'User',
            'Member',
            'Authorization',
        ];

        foreach ($groups as $group) {
            PermissionGroup::create([
                'name' => $group
            ]);
        }
        PermissionGroup::whereNotIn('name', $groups)->delete();
    }
}
