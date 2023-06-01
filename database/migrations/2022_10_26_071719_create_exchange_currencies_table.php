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
        Schema::create('exchange_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sign')->nullable();
            // $table->double('buy_rate')->default(0.0000);
            // $table->double('sell_rate')->default(0.0000);
            $table->unsignedDecimal('buy_rate', 12, 4)->default(0);
            $table->unsignedDecimal('sell_rate', 12, 4)->default(0);
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
        Schema::dropIfExists('exchange_currencies');
    }
};
