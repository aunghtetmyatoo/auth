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
        Schema::create('room_participants', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger("room_id")->nullable()->index();
            $table->foreign("room_id")->references("id")->on("rooms");
            $table->enum("banker_status", [Status::BANKER, Status::BETTOR])->default(Status::BETTOR);
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
        Schema::dropIfExists('room_participants');
    }
};
