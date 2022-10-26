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
        Schema::create('coin_fill_requests', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->text("transaction_screenshot")->nullable();
            $table->enum("admin_transfer_status", [Status::REQUESTED, Status::DONE, Status::REJECTED])->default(Status::REQUESTED)->index();
            $table->unsignedBigInteger('admin_id')->nullable()->index();
            $table->foreign("admin_id")->references("id")->on("admins");
            $table->unsignedBigInteger("payment_type_id")->nullable()->index();
            $table->foreign("payment_type_id")->references("id")->on("payment_types");
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
        Schema::dropIfExists('coin_fill_requests');
    }
};
