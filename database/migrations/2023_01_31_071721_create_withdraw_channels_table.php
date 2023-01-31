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
            $table->unsignedBigInteger("currency_id")->nullable()->index();
            $table->foreign('currency_id')->references('id')->on('exchange_currencies');
            $table->double('min_amount')->default(0);
            $table->double('max_amount')->default(0);
            $table->double('handling_fees')->default(0);
            $table->id('telegram_channel_id');
            $table->boolean('status');
            $table->string('icon_active')->nullable();
            $table->string('icon_inactive')->nullable();
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
