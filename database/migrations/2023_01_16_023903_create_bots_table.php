<?php

use App\Constants\Status;
use App\Constants\MigrationLength;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('name', MigrationLength::NAME)->index();
            $table->double('amount')->default(0.00);
            $table->double('coin')->default(0);
            $table->text('photo')->nullable();
            $table->enum("play", [Status::PLAYING, Status::FREE])->default(Status::FREE);
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('bots');
    }
};
