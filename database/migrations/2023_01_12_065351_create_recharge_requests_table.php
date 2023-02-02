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
        Schema::create('recharge_requests', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->bigIncrements('sequence')->unique();
            $table->unsignedBigInteger("recharge_channel_id")->nullable()->index();
            $table->foreign("recharge_channel_id")->references("id")->on("recharge_channels");
            $table->string('reference_id', MigrationLength::REFERENCE_ID)->unique()->index();
            $table->uuid("user_id")->index();
            $table->foreign("user_id")->references("id")->on("users");
            $table->double('requested_amount')->nullable();
            $table->double('confirmed_amount')->nullable();
            $table->unsignedBigInteger("completed_by")->nullable();
            $table->foreign("completed_by")->references("id")->on("admins");
            $table->double('rate')->nullable();
            $table->double('received_amount')->nullable();
            $table->string('received_from')->nullable();
            $table->string('screenshot')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->enum("status", [Status::REJECTED,Status::CONFIRMED, Status::REQUESTED, Status::CANCELLED,Status::COMPLETED])->default(Status::REQUESTED);
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
        Schema::dropIfExists('recharge_requests');
    }
};
