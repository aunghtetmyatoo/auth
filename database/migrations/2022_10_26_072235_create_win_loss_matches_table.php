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
        Schema::create('win_lose_matches', function (Blueprint $table) {
            $table->id();
            $table->uuid("user_id")->nullable()->index();
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("game_type_id")->nullable()->index();
            $table->foreign("game_type_id")->references("id")->on("game_types");
            $table->unsignedBigInteger("total_match")->default(0);
            $table->unsignedBigInteger("win_match")->default(0);
            $table->unsignedBigInteger("loss_match")->default(0);
            $table->unsignedBigInteger("bet_coin")->default(0);
            $table->unsignedBigInteger("win_coin")->default(0);
            $table->unsignedBigInteger("loss_coin")->default(0);
            $table->unsignedBigInteger("win_streak")->default(0);
            $table->decimal("handle_win_rate", 5, 2)->default(0.6)->nullable();
            $table->decimal("win_rate", 5, 2)->default(0.00)->nullable();
            $table->enum("privacy", [Status::PLAY_WITH_USER, Status::PLAY_WITH_BOT])->nullable();
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
        Schema::dropIfExists('win_loss_matches');
    }
};
