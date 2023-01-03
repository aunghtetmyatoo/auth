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
        Schema::create('game_type_user', function (Blueprint $table) {
            $table->id();
            $table->uuid("user_id")->nullable()->index();
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("game_type_id")->nullable()->index();
            $table->foreign("game_type_id")->references("id")->on("game_types");
            // $table->double('amount')->default(0.00);
            $table->double('coin')->default(0);
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
        Schema::dropIfExists('game_type_user');
    }
};
