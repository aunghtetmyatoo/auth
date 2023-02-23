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
        Schema::create('withdraw_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger("exchange_currency_id")->nullable()->index();
            $table->foreign('exchange_currency_id')->references('id')->on('exchange_currencies');
            $table->unsignedDouble('min_per_transaction')->default(1);
            $table->unsignedDouble('max_per_transaction')->default(1000000);
            $table->unsignedDouble('max_daily')->default(1000000);
            $table->unsignedDouble('handling_fee')->default(0);
            $table->string('telegram_channel_id')->nullable();
            $table->boolean('status')->default(1);
            $table->string('icon_active')->nullable();
            $table->string('icon_inactive')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('withdraw_channels');
    }
};
