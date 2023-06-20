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
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->bigIncrements('sequence')->unique();
            $table->unsignedBigInteger("withdraw_channel_id")->nullable()->index();
            $table->foreign("withdraw_channel_id")->references("id")->on("withdraw_channels");
            $table->string('reference_id', MigrationLength::REFERENCE_ID)->unique()->index();
            $table->uuid("user_id")->index();
            $table->foreign("user_id")->references("id")->on("users");

            $table->unsignedBigInteger("confirmed_by")->nullable();
            $table->foreign("confirmed_by")->references("id")->on("admins");
            $table->unsignedBigInteger("completed_by")->nullable();
            $table->foreign("completed_by")->references("id")->on("admins");
            $table->unsignedBigInteger("refunded_by")->nullable();
            $table->foreign("refunded_by")->references("id")->on("admins");
            $table->unsignedBigInteger("refunding_by")->nullable();
            $table->foreign("refunding_by")->references("id")->on("admins");

            $table->double('rate')->nullable();
            $table->string('payee')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();

            $table->double('amount')->nullable();
            $table->double('handling_fee')->nullable();
            $table->double('transferred_amount')->nullable();
            $table->double('refund_amount')->nullable();

            $table->string('screenshot')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->string(' qr_code')->nullable();
            $table->enum("status", [Status::CONFIRMED, Status::REQUESTED, Status::REFUNDED, Status::COMPLETED,Status::REFUNDING])->default(Status::REQUESTED);
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
        Schema::dropIfExists('withdraw_requests');
    }
};
