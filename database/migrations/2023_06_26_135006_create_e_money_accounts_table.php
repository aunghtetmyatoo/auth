<?php

use App\Constants\MigrationLength;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_money_accounts', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string("reference_code", MigrationLength::REFERENCE_CODE)->unique()->nullable()->index();
            $table->string("reference_id", MigrationLength::REFERENCE_ID)->unique()->index();
            $table->string("account_name", MigrationLength::NAME)->index();
            $table->unsignedDecimal('amount', 15, 4)->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('e_money_accounts');
    }
};
