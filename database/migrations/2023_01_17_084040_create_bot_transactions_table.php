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
            $table->uuid('player_id')->index();
            $table->foreign('player_id')->references('id')->on('users');
            $table->uuid('banker_id')->index();
            $table->foreign('banker_id')->references('id')->on('users');
            $table->string('coin');
            $table->unsignedBigInteger('game_type_id')->index();
            $table->foreign('game_type_id')->references('id')->on('game_types');
            $table->uuid('game_match_id')->index();
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
