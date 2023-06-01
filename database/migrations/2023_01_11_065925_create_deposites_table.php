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
        Schema::create('deposites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('account_name')->nullable();
            $table->string('phone_number');
            // $table->double('amount');
            $table->unsignedDecimal('amount', 12, 4);
            $table->string('transaction_photo')->nullable();
            $table->string('agent_text');
            $table->string('agent_photo');
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
        Schema::dropIfExists('deposites');
    }
};
