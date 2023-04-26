<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(PermissionGroupSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PassportClientSeeder::class);
        $this->call(GameTypeCategorySeeder::class);
        $this->call(GameTypeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PaymentTypeSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(TransactionTypeSeeder::class);
        $this->call(GeneralLedgerSeeder::class);
        $this->call(ExchangeCurrencySeeder::class);
        $this->call(RechargeChannelSeeder::class);
        $this->call(WithdrawChannelSeeder::class);
        $this->call(CashGlSeeder::class);

    }
}
