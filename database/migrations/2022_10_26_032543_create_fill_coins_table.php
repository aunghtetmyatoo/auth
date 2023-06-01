<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\MigrationLength;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fill_coins', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid("user_id")->nullable()->index();
            $table->unsignedBigInteger("admin_id")->nullable()->index();
            // $table->double("transaction_amount")->default(0.00);
            $table->unsignedDecimal('transaction_amount', 12, 4)->default(0);
            $table->unsignedBigInteger("transaction_coins")->default(0);
            $table->unsignedBigInteger("coin_before_transaction")->default(0);
            $table->unsignedBigInteger("coin_after_transaction")->default(0);
            $table->string("reference_id", MigrationLength::REFERENCE_ID)->unique()->index();
            $table->string("status_from_amount", MigrationLength::AMOUNT_STATUS)->nullable();
            $table->string("status_to_amount", MigrationLength::AMOUNT_STATUS)->nullable();
            $table->text("remark", MigrationLength::REMARK)->nullable();
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("admin_id")->references("id")->on("admins");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fill_coins');
    }
};
