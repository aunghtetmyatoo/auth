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
        Schema::create('bot_transactions', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('reference_id');
            $table->unsignedBigInteger("admin_id")->index();
            $table->foreign("admin_id")->references("id")->on("admins");
            $table->uuid("player_id")->index();
            $table->foreign("player_id")->references("id")->on("users");
            $table->string('amount');
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
        Schema::dropIfExists('bot_transactions');
    }
};
