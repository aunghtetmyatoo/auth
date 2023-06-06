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
        Schema::create('deactivated_user_by_operation_manager_logs', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('from_user_id')->index();
            $table->foreign("from_user_id")->references("id")->on("users");
            $table->unsignedBigInteger("to_user_id")->index();
            $table->foreign("to_user_id")->references("id")->on("admins");
            $table->string('reference_id');
            $table->double('amount')->default(0);
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('deactivated_user_by_operation_manager_logs');
    }
};
