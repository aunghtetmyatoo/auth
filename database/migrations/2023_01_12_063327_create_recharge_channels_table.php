<?php

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
        Schema::create('recharge_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger("currency_id")->nullable()->index();
            $table->foreign("currency_id")->references("id")->on("exchange_currencies");
            $table->unsignedDouble('min_per_transaction')->default(1);
            $table->unsignedDouble('max_per_transaction')->default(1000000);
            $table->unsignedDouble('max_daily')->default(1000000);
            $table->unsignedDouble('handling_fees')->default(0);
            $table->unsignedBigInteger('telegram_channel_id');
            $table->dateTime('requests_expired_in')->nullable();
            $table->boolean('status');
            $table->string('qr_code')->nullable();
            $table->string('icon_active')->nullable();
            $table->string('icon_inactive')->nullable();
            $table->string('address');
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
        Schema::dropIfExists('recharge_channels');
    }
};
