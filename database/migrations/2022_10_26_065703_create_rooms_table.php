<?php

use App\Constants\MigrationLength;
use App\Constants\Status;
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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string("name", MigrationLength::NAME)->nullable()->index();
            $table->string("reference_id", MigrationLength::REFERENCE_ID)->unique()->index();
            $table->unsignedBigInteger("room_type_id")->nullable()->index();
            $table->foreign('room_type_id')->references('id')->on('room_types');
            $table->unsignedBigInteger("game_type_id")->nullable()->index();
            $table->foreign('game_type_id')->references('id')->on('game_types');
            $table->unsignedBigInteger("banker_amount")->default(0);
            $table->unsignedBigInteger("min_bet")->default(0);
            $table->enum("privacy", [Status::PUBLIC, Status::PRIVATE]);
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
        Schema::dropIfExists('rooms');
    }
};
