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
        Schema::create('user_matches', function (Blueprint $table) {
            $table->id();
            $table->uuid("user_id")->nullable()->index();
            $table->foreign("user_id")->references("id")->on("users");
            $table->json("card")->nullable();
            $table->json("biggest_card")->nullable();
            $table->tinyInteger("suit")->default(1);
            $table->enum("match_status", [Status::WIN, Status::LOSE]);
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
        Schema::dropIfExists('user_matches');
    }
};
