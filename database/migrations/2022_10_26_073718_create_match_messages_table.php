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
        Schema::create('match_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid("user_id")->nullable()->index();
            $table->foreign("user_id")->references("id")->on("users");
            $table->uuid("match_id")->nullable()->index();
            $table->foreign("match_id")->references("id")->on("matches");
            $table->text("message")->nullable();
            $table->enum("message_type", [Status::STICKER, Status::TEXT])->default(Status::TEXT);
            $table->text("url")->nullable();
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
        Schema::dropIfExists('match_messages');
    }
};
