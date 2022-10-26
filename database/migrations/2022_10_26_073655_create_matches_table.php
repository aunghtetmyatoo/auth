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
        Schema::create('matches', function (Blueprint $table) {
            $table->uuid("id")->unique();
            $table->string("reference_id", MigrationLength::REFERENCE_ID)->unique()->index();
            $table->unsignedBigInteger("room_id")->nullable()->index();
            $table->foreign("room_id")->references("id")->on("rooms");
            $table->json("cards")->nullable();
            $table->unsignedBigInteger("game_type_id")->nullable()->index();
            $table->foreign("game_type_id")->references("id")->on("game_types");
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
        Schema::dropIfExists('matches');
    }
};
