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

        $this->call(PassportClientSeeder::class);

        $this->call(GameTypeSeeder::class);

        $this->call(UserSeeder::class);

        $this->call(PaymentTypeSeeder::class);

        $this->call(AdminSeeder::class);

        $this->call(TransactionTypeSeeder::class);

        $this->call(BotSeeder::class);

        $this->call(GeneralLedgerSeeder::class);
    }
}
