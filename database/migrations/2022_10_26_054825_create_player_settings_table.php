<?php

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
        Schema::create('player_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid("user_id")->nullable()->index();
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("game_type_id")->nullable()->index();
            $table->foreign("game_type_id")->references("id")->on("game_types");
            $table->enum("sound_status", [Status::OPEN, Status::CLOSE])->default(Status::OPEN);
            $table->enum("vibration_status", [Status::OPEN, Status::CLOSE])->default(Status::OPEN);
            $table->enum("challenge_status", [Status::OPEN, Status::CLOSE])->default(Status::OPEN);
            $table->enum("friend_status", [Status::OPEN, Status::CLOSE])->default(Status::OPEN);
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
        Schema::dropIfExists('player_settings');
    }
};
