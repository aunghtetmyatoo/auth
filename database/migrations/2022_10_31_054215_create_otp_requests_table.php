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
        Schema::create('otp_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('blocked_by')->nullable();
            $table->foreign('blocked_by')->references('id')->on('admins');
            $table->string('browser_id');
            $table->string('phone_number');
            $table->string('action');
            $table->boolean('is_blocked')->default(0);
            $table->dateTime('blocked_at')->nullable();
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
        Schema::dropIfExists('otp_requests');
    }
};
