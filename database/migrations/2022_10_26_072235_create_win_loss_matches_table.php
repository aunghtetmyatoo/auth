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
        Schema::create('win_loss_matches', function (Blueprint $table) {
            $table->id();
            $table->uuid("user_id")->nullable()->index();
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("game_type_id")->nullable()->index();
            $table->foreign("game_type_id")->references("id")->on("game_types");
            $table->unsignedBigInteger("win_match")->nullable();
            $table->unsignedBigInteger("loss_match")->nullable();
            $table->unsignedBigInteger("total_match")->nullable();
            $table->double("bet_amount")->default(0);
            $table->unsignedBigInteger("bet_coin")->nullable();
            $table->unsignedBigInteger("win_coin")->nullable();
            $table->unsignedBigInteger("loss_coin")->nullable();
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
        Schema::dropIfExists('win_loss_matches');
    }
};
