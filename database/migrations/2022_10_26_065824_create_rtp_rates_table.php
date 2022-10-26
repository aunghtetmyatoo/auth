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
        Schema::create('rtp_rates', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("rtp_rate")->nullable();
            $table->unsignedBigInteger("game_type_id")->nullable();
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
        Schema::dropIfExists('rtp_rates');
    }
};
